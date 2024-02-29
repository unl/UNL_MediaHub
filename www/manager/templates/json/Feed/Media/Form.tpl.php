<?php

$details = array(
    'transcoding_is_complete' => false,
    'transcription_is_complete' => false
);

$transcoding_job = $context->media->getMostRecentTranscodingJob();
if ($transcoding_job) {
    $details['transcoding_is_complete'] = $transcoding_job->isFinished();
}

$ai_caption_job = $context->media->getMostRecentTranscriptionJob();
if ($ai_caption_job) {
    $details['transcription_is_complete'] = $ai_caption_job->isFinished();
}

echo json_encode($details);