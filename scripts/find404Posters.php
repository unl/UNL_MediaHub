<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediahub = new UNL_MediaHub();

$list = new UNL_MediaHub_MediaList(array(
    'filter' => new UNL_MediaHub_MediaList_Filter_WithPoster()
));

$list->options['limit'] = 3000;
$list->run();

if (count($list->items)) {
    foreach ($list->items as $media) {
        if (empty($media->poster)) {
            continue;
        }
        
        // Try and get the poster
        if (substr($media->poster, 0, 5) == 'http:'
            || substr($media->poster, 0, 6) == 'https:') {
            $context = stream_context_create(array('http'=>array(
                'method'     => 'HEAD',
                'user_agent' => 'UNL MediaHub/mediahub.unl.edu'
            )));

            if ($result = @file_get_contents($media->poster, null, $context, -1, 8)) {
                // Assume OK, $result would === false if there was a 404
                continue;
            }

            if (!isset($http_response_header) || 
                false === $http_response_header) {
                echo 'DNS failure, did the server move?'.PHP_EOL;
            }

            if (!isset($http_response_header[0])
                || strpos($http_response_header[0], '200') === false) {
                // This file is GONE! Better remove it
                echo 'REMOVING POSTER-'.PHP_EOL.'ID:    '.$media->id.PHP_EOL.'Title: '.$media->title.PHP_EOL.'POSTER URL:   '.$media->poster.PHP_EOL.PHP_EOL;
                $media->poster = '';
                $media->save();
            }
        }
    }
}
