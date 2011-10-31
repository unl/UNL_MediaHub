<?php

class UNL_MediaHub_Feed_Media_Form
{
    public $action;
    
    public $media;
    
    function __construct(UNL_MediaHub_Media $media = null)
    {
        if (isset($media)) {
            $this->media = $media;
            
            foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $class) {
                $this->media->loadReference($class);
            }
        }
    }
}