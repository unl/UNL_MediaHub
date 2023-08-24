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
$db = Doctrine_Manager::getInstance()->getCurrentConnection();

while (true) {
    if (!$db->isConnected()) {
        $db->connect();
    }
    
    //Get all orders that have not been completed.
    $media_hub_jobs = new UNL_MediaHub_TranscodingJobList(array('all_not_complete' => true));

    /**
     * Order status life cycle:
     * 1. STATUS_SUBMITTED (order was created by mediahub, not yet created in aws)
     *      - upload video to s3, create job in mediaConvert
     * 2. STATUS_WORKING (order has been sent to aws, aws job ID has been saved)
     * ... poll AWS job for status
     * 3. STATUS_FINISHED (finished in aws, copied and deleted from s3)
     */

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
        $input_key = $media->UUID . '.' . $input_extension;
        $input_arn = 's3://' . $input_bucket . '/' . $input_key;
        $output_arn = 's3://' . $output_bucket . '/' . $media->UUID . '/media';

        switch ($media_hub_job->status) {
            case UNL_MediaHub_TranscodingJob::STATUS_SUBMITTED:
                echo date("Y-m-d H:i:s") . ": Creating job for " . $media_hub_job->id . PHP_EOL;

                //1. upload to s3
                upload($source, $input_bucket, $input_key);

                $aspect_ratio = $media->getAspectRatio();
                $video_dimensions = $media->findVideoDimensions();

                $max_dimension = '720';
                if ($video_dimensions !== false && $video_dimensions['height'] >= $video_dimensions['width']) {
                    $max_dimension = $video_dimensions['height'];
                } elseif ($video_dimensions !== false && $video_dimensions['height'] < $video_dimensions['width']) {
                    $max_dimension = $video_dimensions['width'];
                }

                //2. create job
                if ('hls' === $media_hub_job->job_type) {
                    $jobId = createHlsJob(
                        UNL_MediaHub::$transcode_mediaconvert_api_endpoint,
                        UNL_MediaHub::$transcode_mediaconvert_role,
                        $input_arn,
                        $output_arn,
                        $aspect_ratio,
                        $max_dimension
                    );
                } else {
                    //default to an mp4 job
                    $jobId = createMp4Job(
                        UNL_MediaHub::$transcode_mediaconvert_api_endpoint,
                        UNL_MediaHub::$transcode_mediaconvert_role,
                        $input_arn,
                        $output_arn,
                        $aspect_ratio
                    );
                }

                $media_hub_job->job_id = $jobId;
                $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_WORKING;
                echo "\tJob Created" . PHP_EOL;
                break;
            case UNL_MediaHub_TranscodingJob::STATUS_WORKING:
                $mediaConvert = new \Aws\MediaConvert\MediaConvertClient([
                    'version' => '2017-08-29',
                    'region' => 'us-east-1',
                    'endpoint' => UNL_MediaHub::$transcode_mediaconvert_api_endpoint,
                ]);

                $job = $mediaConvert->getJob(array('Id' => $media_hub_job->job_id));
                $jobData = $job->get('Job');
                //Status will be one of SUBMITTED|PROGRESSING|COMPLETE|CANCELED|ERROR
                $jobData['Status'];

                if (in_array($jobData['Status'], array('SUBMITTED', 'PROGRESSING'))) {
                    //Still working... nothing to do... just update the dateupdated field
                    break;
                } else if (in_array($jobData['Status'], array('CANCELED', 'ERROR'))) {
                    echo date("Y-m-d H:i:s") . ": Error for job " . $media_hub_job->id . PHP_EOL;
                    //Log error message
                    $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_ERROR;
                    $media_hub_job->error_text = $jobData['ErrorCode'] . ' : ' . $jobData['ErrorMessage'];

                    //delete input file from S3
                    deleteInputFile($input_bucket, $input_key);

                    echo "\tERROR: " . $media_hub_job->error_text;
                } else {
                    echo date("Y-m-d H:i:s") . ": Job completed: " . $media_hub_job->id . PHP_EOL;
                    //it completed!
                    //Copy it over
                    downloadOutput($output_bucket, $media->UUID, $media);

                    //Delete input and output files
                    deleteInputFile($input_bucket, $input_key);
                    deleteOutputDirectory($output_bucket, $media->UUID);

                    //Update the job
                    $media_hub_job->status = UNL_MediaHub_TranscodingJob::STATUS_FINISHED;

                    //Do some cache-busting on the media object
                    $media->dateupdated = date('Y-m-d H:i:s');

                    echo "\trecord updated and s3 objects cleaned up " . $media_hub_job->id . PHP_EOL;
                }

                break;
            default:
                throw new \Exception('This code should never execute');
        }

        $media_hub_job->dateupdated = date('Y-m-d H:i:s');
        $media_hub_job->save();
        
        if ($media_hub_job->isFinished()) {
            exit(12); //Restart the daemon after every completed job to help keep things fresh
        }
    }

    //Always wait a little bit between iterations
    sleep(10);
}

function downloadOutput($output_bucket, $key, UNL_MediaHub_Media $media)
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
    
    $url = UNL_MediaHub_Controller::getURL() . 'uploads/'.$media->UUID.'/media.mp4';
    
    // For new media, we should delete the original upload and change the URL for the mp4 file
    // For swaps, the URL should be the same and the newly uploaded file will be replaced by the version from aws
    // because it should have the same name (media.mp4)
    if ($url !== $media->url) {
        //Delete the original upload to save space
        unlink($media->getLocalFileName());
        //Update the URL of the media to the media.mp4 file
        $media->url = $url;
        $media->save();
    }

    $poster_file = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/'.$media->id.'/original.jpg';
    if (file_exists($poster_file)) {
        //Delete the poster file so that a new one is generated
        unlink($poster_file);
    }

    $poster_file = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/'.$media->id.'/use-default';
    if (file_exists($poster_file)) {
        //the default was selected, delete it so that a new one is generated
        unlink($poster_file);
    }
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

    $s3->deleteMatchingObjects($output_bucket, $key);
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
        if ($tries > 5) {
            throw new \Exception("Error while uploading, tried 5 times.");
        }
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
    } while (!isset($result));
}

function createMp4Job($endpoint, $role, $input, $output, $aspect_ratio)
{
    $mediaConvert = new \Aws\MediaConvert\MediaConvertClient([
        'version' => '2017-08-29',
        'region'  => 'us-east-1',
        'endpoint' => $endpoint,
    ]);

    //Default to 16:9
    $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.mp4.16x9.template.json');

    //Use 9x16 if we need to
    if (UNL_MediaHub_Media::ASPECT_9x16 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.mp4.9x16.template.json');
    }

    //Use 4:3 if we need to
    if (UNL_MediaHub_Media::ASPECT_4x3 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.mp4.4x3.template.json');
    }

    //Use 3:4 if we need to
    if (UNL_MediaHub_Media::ASPECT_3x4 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.mp4.3x4.template.json');
    }

    //Use 1:1 if we need to
    if (UNL_MediaHub_Media::ASPECT_1x1 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.mp4.1x1.template.json');
    }

    $job_settings = json_decode($job_settings, true);

    //We are not using a job template hosted in aws because we need to customize the destination
    $job_settings['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $output;
    $job_settings['Settings']['Inputs'][0]['FileInput'] = $input;
    $job_settings['Role'] = $role;

    $job = $mediaConvert->createJob($job_settings);

    // get the job data as array
    $jobData = $job->get('Job');
    return $jobData['Id'];
}

function createHlsJob($endpoint, $role, $input, $output, $aspect_ratio, $max_dimension)
{
    $mediaConvert = new \Aws\MediaConvert\MediaConvertClient([
        'version' => '2017-08-29',
        'region'  => 'us-east-1',
        'endpoint' => $endpoint,
    ]);

    ///Default to 16:9
    $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.16x9.template.json');
    $formatted_aspect_ratio = '16x9';

    //Use 9x16 if we need to
    if (UNL_MediaHub_Media::ASPECT_9x16 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.9x16.template.json');
        $formatted_aspect_ratio = '9x16';
    }

    //Use 4:3 if we need to
    if (UNL_MediaHub_Media::ASPECT_4x3 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.4x3.template.json');
        $formatted_aspect_ratio = '4x3';
    }

    //Use 3:4 if we need to
    if (UNL_MediaHub_Media::ASPECT_3x4 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.3x4.template.json');
        $formatted_aspect_ratio = '3x4';
    }

    //Use 1:1 if we need to
    if (UNL_MediaHub_Media::ASPECT_1x1 == $aspect_ratio) {
        $job_settings = file_get_contents(__DIR__.'/../data/mediaconvert.hls.1x1.template.json');
        $formatted_aspect_ratio = '1x1';
    }

    $HLS_adaptive_outputs = array();

    // Some times these are like right under the actual value so I like to bump it up by a small bit
    $small_bump = 50;

    if ($max_dimension+$small_bump >= '480') {
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 480p " . $formatted_aspect_ratio . " single pass",
            "NameModifier" => "480p"
        );
    }
    if ($max_dimension+$small_bump >= '540') {
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 540p " . $formatted_aspect_ratio . " single pass",
            "NameModifier" => "540p"
        );
    }
    if ($max_dimension+$small_bump >= '720') {
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 720p " . $formatted_aspect_ratio . " single pass",
            "NameModifier" => "720p"
        );
    }
    if ($max_dimension+$small_bump >= '1080') {
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 1080p " . $formatted_aspect_ratio . " single pass",
            "NameModifier" => "1080p"
        );
    }
    if ($max_dimension+$small_bump >= '2160') {
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 2160p " . $formatted_aspect_ratio . " single pass",
            "NameModifier" => "2160p"
        );
        $HLS_adaptive_outputs[] = array(
            "Preset" => "HLS 2160p " . $formatted_aspect_ratio . " 32mbps single pass",
            "NameModifier" => "2160p32mbps"
        );
    }

    $job_settings = json_decode($job_settings, true);

    //We are not using a job template hosted in aws because we need to customize the destination
    $job_settings['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $output;
    $job_settings['Settings']['OutputGroups'][1]['OutputGroupSettings']['HlsGroupSettings']['Destination'] = $output;
    $job_settings['Settings']['OutputGroups'][1]['Outputs'] = $HLS_adaptive_outputs;
    $job_settings['Settings']['Inputs'][0]['FileInput'] = $input;
    $job_settings['Role'] = $role;

    $job = $mediaConvert->createJob($job_settings);

    // get the job data as array
    $jobData = $job->get('Job');
    return $jobData['Id'];
}
