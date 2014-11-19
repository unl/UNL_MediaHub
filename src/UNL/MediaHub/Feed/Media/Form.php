<?php

class UNL_MediaHub_Feed_Media_Form
{
    public $action;

    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    /**
     * @var UNL_MediaHub_Feed_Media_FeedSelection
     */
    public $feed_selection;
    
    function __construct(UNL_MediaHub_Media $media = null)
    {
        if (isset($media)) {
            $this->media = $media;
            
            foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $class) {
                $this->media->loadReference($class);
            }
        }
 
        $this->feed_selection = new UNL_MediaHub_Feed_Media_FeedSelection(UNL_MediaHub_Manager::getUser(), $this->media);
    }
}