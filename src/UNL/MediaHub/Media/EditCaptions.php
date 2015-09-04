<?php

class UNL_MediaHub_Media_EditCaptions
{
    public $options = array();
    
    public function __construct($options = array())
    {
        $this->options = $options;
        
        if (!isset($options['id'])) {
            throw new \Exception('You must pass a media ID');
        }
        
        if (!$this->media = UNL_MediaHub_Media::getById($options['id'])) {
            throw new \Exception('Could not find that media', 404);
        }
    }
    
    public function getTrackHistory()
    {
        return new UNL_MediaHub_MediaTextTrackList(array('media_id'=>$this->media->id));
    }
}
