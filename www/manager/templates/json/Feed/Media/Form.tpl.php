<?php

$details = array(
    'transcoding_is_complete' => false
);

$job = $context->media->getMostRecentTranscodingJob();

if ($job) {
    $details['transcoding_is_complete'] = $job->isFinished();
}

echo json_encode($details);