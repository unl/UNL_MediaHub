<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

$list = new UNL_MediaYak_MediaList();

$list->run();

if (count($list->items)) {
    foreach ($list->items as $media) {
        // Try and get the media
        if (substr($media->url, 0, 5) == 'http:') {
            $headers = get_headers($media->url);
            foreach ($headers as $header) {
                if (strpos($header, 'HTTP/1.1 404 Not Found') !== false) {
                    // This file is GONE! Better remove it
                    echo 'REMOVING -'.PHP_EOL.'ID:    '.$media->id.PHP_EOL.'Title: '.$media->title.PHP_EOL.'URL:   '.$media->url.PHP_EOL.PHP_EOL;
                    $media->delete();
                }
            }
        }
    }
}
