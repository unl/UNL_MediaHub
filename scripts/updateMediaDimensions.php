<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

// Set up system with DSN
$mediayak = new UNL_MediaYak($dsn);

UNL_MediaYak_MediaList::$results_per_page = 100;
$list = new UNL_MediaYak_MediaList();
if (count($list->items)) {
    foreach ($list->items as $media) {
        // @var UNL_MediaYak_Media $media
        list($width, $height) = getimagesize(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($media->url));
        if ($element = UNL_MediaYak_Feed_Media_NamespacedElements::mediaHasElement($media->id, 'content', 'mrss')) {
            // all good
        } else {
            $element = new UNL_MediaYak_Feed_Media_NamespacedElements_mrss();
            $element->media_id = $media->id;
            $element->element = 'content';
        }
        $attributes = array('width'=>$width, 'height'=>$height);
        if (isset($element->attributes) && is_array($element->attributes)) {
            $attributes = array_merge($element->attributes, $attributes);
        } 
        $element->attributes = $attributes;
        $element->save();
        echo 'saved'.PHP_EOL;
    }
}

?>