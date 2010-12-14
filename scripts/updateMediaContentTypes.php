<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

//UNL_MediaYak_MediaList::$results_per_page = 100;
$list = new UNL_MediaYak_MediaList(array('filter'=>new UNL_MediaYak_MediaList_Filter_NoContentType()));
$list->run();
if (count($list->items)) {
    echo count($list->items).' media missing content types found.'.PHP_EOL;
    foreach ($list->items as $media) {
        $media->setContentType();
        $media->save();
    }
}
?>
