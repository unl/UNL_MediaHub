<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

// Set up system with DSN
$mediayak = new UNL_MediaYak($dsn);

UNL_MediaYak_MediaList::$results_per_page = 500;
$list = new UNL_MediaYak_MediaList();
if (count($list->items)) {
    foreach ($list->items as $media) {
        // @var UNL_MediaYak_Media $media
        $media->setMRSSContent();
        $media->setMRSSThumbnail();
        echo 'saved'.PHP_EOL;
    }
}

?>