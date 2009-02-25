<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

require_once 'Validate.php';

$mediayak = new UNL_MediaYak($dsn);

UNL_MediaYak_MediaList::$results_per_page = 100;
$list = new UNL_MediaYak_MediaList(new UNL_MediaYak_MediaList_Filter_NoContentType());
if (count($list->items)) {
    foreach ($list->items as $media) {
        $media->setContentType();
        if ($media->save()) {
            echo 'Saved file '.$media->url.PHP_EOL;
        }
    }
}
?>