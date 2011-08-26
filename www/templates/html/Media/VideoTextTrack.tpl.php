<?php

// Try grabbing the vtt from Universal Subtitles
$vtt = @file_get_contents('http://www.universalsubtitles.org/api/1.0/subtitles/?video_url='.$context->url.'&sformat=srt');

if ($vtt === false
    || $vtt == '') {
	$savvy->render(new Exception('No text track found', 404));
}

echo $vtt;