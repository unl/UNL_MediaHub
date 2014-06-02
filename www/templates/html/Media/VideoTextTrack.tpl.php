<?php

$track = false;

$ctx = stream_context_create(array(
    'http' => array(
        'timeout' => 5,
        )
    )
); 

// Check if universal subtitles has info on this media
if ($info_json = @file_get_contents('http://www.amara.org/api2/partners/videos/?video_url='.$context->url.'&format=json', false, $ctx)) {
    $json = json_decode($info_json);


    $format = 'vtt';

    if (in_array($controller->options['format'], array('vtt','srt'))) {
        $format = $controller->options['format'];
    }
    
    if ($json->meta->total_count >= 1) {
        // Try grabbing the vtt or srt from Universal Subtitles
        $track = @file_get_contents('http://www.amara.org/api2/partners/videos/'.$json->objects[0]->id.'/languages/en/subtitles/?format='.$format, false, $ctx);
    }
}

if ($track === false
    || $track == '') {
    $savvy->render(new Exception('No text track found', 404));
}

echo $track;
