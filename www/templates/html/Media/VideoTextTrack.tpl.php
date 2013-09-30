<?php

$vtt = false;

// Check if universal subtitles has info on this media
if ($info_json = @file_get_contents('http://www.amara.org/api2/partners/videos/?video_url='.$context->url.'&format=json')) {
    $json = json_decode($info_json);

    if ($json->meta->total_count >= 1) {
        // Try grabbing the vtt from Universal Subtitles
        $vtt = @file_get_contents('http://www.amara.org/api2/partners/videos/'.$json->objects[0]->id.'/languages/en/subtitles/?format=vtt');
    }
}

if ($vtt === false
    || $vtt == '') {
    $savvy->render(new Exception('No text track found', 404));
}

echo $vtt;
