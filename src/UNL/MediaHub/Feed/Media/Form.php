<?php

class UNL_MediaHub_Feed_Media_Form
{
    public $action;
    
    public $media;
    
    function __construct(UNL_MediaHub_Media $media = null)
    {
        if (isset($media)) {
            $this->media = $media;
            $this->media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_itunesu');
            $this->media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_itunes');
            $this->media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_media');
        }
    }
}