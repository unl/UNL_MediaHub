<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediahub = new UNL_MediaHub();

$db = $mediahub->getDB()->getDbh();
$collection = $db->query('select id from media where duration = 0');

foreach ($collection as $row) {
    /**
     * @var \UNL_MediaHub_Media $media
     */
    $media = UNL_MediaHub_Media::getById($row['id']);

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
