<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}

//Establish mediahub connection
$media_hub = new UNL_MediaHub();
$TranscriptionAPI = new UNL_MediaHub_TranscriptionAPI();
$db = Doctrine_Manager::getInstance()->getCurrentConnection();

$start_date = time();
$total_time_running = 24;

while (true) {
    if (!$db->isConnected()) {
        $db->connect();
    }
    
    //Get all orders that have not been completed.
    $media_hub_jobs = new UNL_MediaHub_TranscriptionJobList(array('all_not_complete' => true));

    //Loop through them and check with Rev.com to see their status.
    foreach ($media_hub_jobs->items as $media_hub_job) {
        /**
         * This is only here for autocomplete
         * @var UNL_MediaHub_TranscriptionJob $captioning_job
         */
        $captioning_job = $media_hub_job;

        $status = $TranscriptionAPI->get_status($captioning_job->job_id);
        if ($status === 'finished') {
            $captioning_job->status = 'FINISHED';
            $captioning_job->save();

            $captions = $TranscriptionAPI->get_captions($captioning_job->job_id);

            // copy track
            $newTrack = new UNL_MediaHub_MediaTextTrack();
            $newTrack->media_id = $captioning_job->media_id;
            $newTrack->source = 'ai transcriptionist';
            $newTrack->save();

            // copy track file
            $newTrackFile = new UNL_MediaHub_MediaTextTrackFile();
            $newTrackFile->media_text_tracks_id = $newTrack->id;
            $newTrackFile->kind = 'caption';
            $newTrackFile->format = 'vtt';
            $newTrackFile->language = 'en';
            $newTrackFile->file_contents = $captions;
            $newTrackFile->save();
        }

        if ($status === 'error') {
            $captioning_job->status = 'ERROR';
            $captioning_job->save();
        }

        usleep(500 * 1000); // 500 Milliseconds
    }

    // Checks how long loop has been running and will shut it down if its been too long
    if (time() - $start_date > $total_time_running) {
        echo "Restarting due to age.";
        break;
    }
}

