<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}

//Establish mediahub connection
$media_hub = new UNL_MediaHub();

$db = Doctrine_Manager::getInstance()->getCurrentConnection();

//Get all orders that have not been completed.
$media_list = new UNL_MediaHub_MediaList(array('limit'=>10000));

foreach ($media_list->items as $media) {
    echo $media->id . PHP_EOL;
    
    if (empty($media->media_text_tracks_id)) {
        $result = $media->updateAmaraCaptions();
        
        if ($result) {
            echo "\t Captions Updated" . PHP_EOL;
        }

        sleep(1);
    }
}

echo '--FINISHED--';
