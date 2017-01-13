<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediahub = new UNL_MediaHub();

$list = new UNL_MediaHub_MediaList(['limit'=>99999]);

$list->run();

if (!count($list->items)) {
    echo 'no records found' . PHP_EOL;
    exit();
}

foreach ($list->items as $media) {
    /**
     * @var \UNL_MediaHub_Media $media
     */
    
    if (!$media->getLocalFileName()) {
        //Skip non-local media
        continue;
    }
    
    if (!empty($media->duration)) {
        //We already have the duration... skip
        continue;
    }

    if (!$duration = $media->findDuration()) {
        //unable to find duration... skip
        continue;
    }
    
    //Update and save
    $media->duration = $duration->getTotalMilliseconds();
    $media->save();
    
    echo $media->id . ' updated ' . PHP_EOL;
}
