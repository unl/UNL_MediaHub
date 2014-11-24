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
    
    public $options = array();
    
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        
        if (isset($this->options['id'])) {
            if (!$this->media = UNL_MediaHub_Media::getById($_GET['id'])) {
                throw new Exception('Media could not be found.', 404);
            }

            if (!$this->media->userCanEdit(UNL_MediaHub_Manager::getUser())) {
                throw new Exception('You do not have permission to edit this media.', 403);
            }

            foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $class) {
                $this->media->loadReference($class);
            }
        }
 
        $this->feed_selection = new UNL_MediaHub_Feed_Media_FeedSelection(UNL_MediaHub_Manager::getUser(), $this->media);
    }
}