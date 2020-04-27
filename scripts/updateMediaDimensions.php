<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

// Set up system with DSN
$mediahub = new UNL_MediaHub();

$filter = null;
if (isset($_SERVER['argv'],$_SERVER['argv'][1])) {
    $filter = new UNL_MediaHub_MediaList_Filter_TextSearch($_SERVER['argv'][1]);
}

$options = array('filter' => $filter,
                 'limit'  => 100);
$list = new UNL_MediaHub_MediaList($options);
$list->run();
if (count($list->items)) {
    foreach ($list->items as $media) {
        // @var UNL_MediaHub_Media $media
        $media->setMRSSContent();
        $media->setMRSSThumbnail();
        echo 'saved'.PHP_EOL;
    }
}

?>
