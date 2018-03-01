<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}

use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

//Establish mediahub connection
$media_hub = new UNL_MediaHub();

//TODO: what about re-establishing a connection?

//TODO: do we need this?
//$db = Doctrine_Manager::getInstance()->getCurrentConnection();

//Get all orders that have not been completed.
$media_hub_jobs = new UNL_MediaHub_TranscodingJobList(array('all_not_complete'=>true));

/**
 * Order status life cycle:
 * 1. STATUS_SUBMITTED (order was created by mediahub, not yet created in aws)
 *      - upload video to s3, create job in mediaConvert
 * 2. STATUS_WORKING (order has been sent to aws, aws job ID has been saved)
 * ... poll AWS job for status
 * 3. STATUS_FINISHED (finished in aws, copied and deleted from s3)
 */

$rev = UNL_MediaHub_RevAPI::getRevClient();

if (!$rev) {
    throw new Exception('Unable to get the Rev client', 500);
}

$has_output = false;

//Loop through them and check with Rev.com to see their status.
foreach ($media_hub_jobs->items as $media_hub_job) {
    /**
     * @var $media_hub_job UNL_MediaHub_TranscodingJob
     */

    $media = $media_hub_job->getMedia();

    $input_bucket = UNL_MediaHub::$transcode_input_bucket;
    $output_bucket = UNL_MediaHub::$transcode_output_bucket;
    $source = $media->getLocalFileName();
    $input_extension = pathinfo($source, PATHINFO_EXTENSION);
    $input_key = $media->UUID.'.'.$input_extension;
    $input_arn = 's3://'.$input_bucket . '/' . $input_key;
    $output_arn = 's3://'.$output_bucket . '/'.$media->UUID . '/media';

    switch ($media_hub_job->status) {
        case UNL_MediaHub_TranscodingJob::STATUS_SUBMITTED:
            echo "Creating job for " . $media_hub_job->id . PHP_EOL;
            
            //1. upload to s3
            upload($source, $input_bucket, $input_key);
            
            $aspect_ratio = $media->getAspectRatio();
            
            //2. create job
            $jobId = createHlsJob(
                UNL_MediaHub::$transcode_mediaconvert_api_endpoint,
                UNL_MediaHub::$transcode_mediaconvert_role,
                $input_arn,
                $output_arn,
                $aspect_ratio
            );
            
            $media_hub_job->job_id = $jobId;
            $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_WORKING;
            echo "\tJob Created". PHP_EOL;
            break;
        case UNL_MediaHub_TranscodingJob::STATUS_WORKING:
            $mediaConvert = new \Aws\MediaConvert\MediaConvertClient([
                'version' => '2017-08-29',
                'region'  => 'us-east-1',
                'endpoint' => UNL_MediaHub::$transcode_mediaconvert_api_endpoint,
            ]);
            
            $job = $mediaConvert->getJob(array('Id'=>$media_hub_job->job_id));
            $jobData = $job->get('Job');
            //Status will be one of SUBMITTED|PROGRESSING|COMPLETE|CANCELED|ERROR
            $jobData['Status'];
            
            if (in_array($jobData['Status'], array('SUBMITTED', 'PROGRESSING'))) {
                //Still working... nothing to do... just update the dateupdated field
                break;
            } else if (in_array($jobData['Status'], array('CANCELED', 'ERROR'))) {
                echo "Error for job " . $media_hub_job->id . PHP_EOL;
                //Log error message
                $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_ERROR;
                $media_hub_job->error = $jobData['ErrorCode'] . ' : ' . $jobData['ErrorMessage'];
                
                //delete input file from S3
                deleteInputFile($input_bucket, $input_key);
                
                echo "\tERROR: " . $media_hub_job->error;
            } else {
                echo "Job completed: " . $media_hub_job->id . PHP_EOL;
                //it completed!
                //Copy it over
                downloadOutput($output_bucket, $media->UUID, $source);
                
                //Delete input and output files
                deleteInputFile($input_bucket, $input_key);
                deleteOutputDirectory($input_bucket, $media->UUID);
                
                //Update the job
                $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_FINISHED;
                
                //Do some cache-busting on the media object
                $media->dateupdated = date('Y-m-d H:i:s');
                
                echo "\trecord updated and s3 objected cleaned up " . $media_hub_job->id . PHP_EOL;
            }
            
            break;
        default:
            throw new \Exception('This code should never execute');
    }

    $media_hub_job->dateupdated = date('Y-m-d H:i:s');
    $media_hub_job->save();
}

if ($has_output) {
    echo "--FINISHED--" . PHP_EOL;
}

function downloadOutput($output_bucket, $key, $original_file)
{
    $s3 = new Aws\S3\S3Client([
        'version' => '2006-03-01',
        'region'  => 'us-east-1'
    ]);
    
    $download_dir = UNL_MediaHub_Manager::getUploadDirectory().'/'.$key;
    
    if (!file_exists($download_dir)) {
        //Create the download directory
        mkdir($download_dir);
    }
    
    $s3->downloadBucket($download_dir, $output_bucket, '/'.$key);

    //Replace the original file with the new 720mp4 to simplify logic and save space
    rename($download_dir.'/media720.mp4', $original_file);
}

function deleteInputFile($input_bucket, $key)
{
    $s3 = new Aws\S3\S3Client([
        'version' => '2006-03-01',
        'region'  => 'us-east-1'
    ]);

    $s3->deleteMatchingObjects($input_bucket, $key);
}

function deleteOutputDirectory($output_bucket, $key)
{
    $s3 = new Aws\S3\S3Client([
        'version' => '2006-03-01',
        'region'  => 'us-east-1'
    ]);

    $s3->deleteMatchingObjects($output_bucket, '/'.$key);
}


function upload($source, $input_bucket, $key)
{
    $s3 = new Aws\S3\S3Client([
        'version' => '2006-03-01',
        'region'  => 'us-east-1'
    ]);

    $uploader = new MultipartUploader($s3, $source, [
        'bucket' => $input_bucket,
        'key'    => $key,
    ]);

    //Try 5 times...
    $tries = 0;
    do {
        try {
            $result = $uploader->upload();
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . "\n";
            echo 'retrying' . PHP_EOL;
            $uploader = new MultipartUploader($s3, $source, [
                'state' => $e->getState(),
            ]);
        }
        $tries++;
    } while (!isset($result) && $tries < 5);
}

function createHlsJob($endpoint, $role, $input, $output, $aspect_ratio)
{
    $mediaConvert = new \Aws\MediaConvert\MediaConvertClient([
        'version' => '2017-08-29',
        'region'  => 'us-east-1',
        'endpoint' => $endpoint,
    ]);

    //Default to 16:9
    $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.16x9.template.json');
    
    //Use 4:3 if we need to
    if (UNL_MediaHub_Media::ASPECT_4x3 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.4x3.template.json');
    }
    
    $job_settings = json_decode($job_settings, true);

    //We are not using a job template hosted in aws because we need to customize the destination
    $job_settings['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $output;
    $job_settings['Settings']['OutputGroups'][1]['OutputGroupSettings']['HlsGroupSettings']['Destination'] = $output;
    $job_settings['Settings']['Inputs'][0]['FileInput'] = $input;
    $job_settings['Role'] = $role;

    $job = $mediaConvert->createJob($job_settings);

    // get the job data as array
    $jobData = $job->get('Job');
    return $jobData['Id'];
}