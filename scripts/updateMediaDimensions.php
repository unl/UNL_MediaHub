<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

// Set up system with DSN
$mediayak = new UNL_MediaYak($dsn);

$filter = null;
if (isset($_SERVER['argv'],$_SERVER['argv'][1])) {
    $filter = new UNL_MediaYak_MediaList_Filter_TextSearch($_SERVER['argv'][1]);
}

UNL_MediaYak_MediaList::$results_per_page = 30;
$list = new UNL_MediaYak_MediaList($filter);
if (count($list->items)) {
    foreach ($list->items as $media) {
        // @var UNL_MediaYak_Media $media
        $media->setMRSSContent();
        $media->setMRSSThumbnail();
        echo 'saved'.PHP_EOL;
    }
}

?>
