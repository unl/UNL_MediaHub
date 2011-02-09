<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

$list = new UNL_MediaYak_MediaList();
$list->options['limit'] = 3000;
$list->run();

if (count($list->items)) {
    foreach ($list->items as $media) {
        // Try and get the media
        if (substr($media->url, 0, 5) == 'http:') {
            $context = stream_context_create(array('http'=>array(
                'method'     => 'GET',
                'user_agent' => 'UNL MediaHub/mediahub.unl.edu'
                )));

            if ($result = @file_get_contents($media->url, null, $context, -1, 8)) {
                // Assume OK, $result would === false if there was a 404
                continue;
            }

            if (false === $http_response_header) {
                echo 'DNS failure, did the server move?'.PHP_EOL;
            }

            foreach ($http_response_header as $header) {
                if (strpos($header, 'HTTP/1.1 404 Not Found') !== false) {
                    // This file is GONE! Better remove it
                    echo 'REMOVING -'.PHP_EOL.'ID:    '.$media->id.PHP_EOL.'Title: '.$media->title.PHP_EOL.'URL:   '.$media->url.PHP_EOL.PHP_EOL;
                    $media->delete();
                }
            }
        }
    }
}
