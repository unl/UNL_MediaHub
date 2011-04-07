<?php
class UNL_MediaYak_Feed_LiveStream
{

    public $feed;
    
    function __construct($options = array())
    {
        if (!empty($options['feed'])) {
            $this->feed = $options['feed'];
        } elseif (!empty($options['feed_id'])) {
            $this->feed = UNL_MediaYak_Feed::getById($options['feed_id']);
        } elseif (!empty($options['title'])) {
            $this->feed = UNL_MediaYak_Feed::getByTitle($this->options['title']);
        }
    }
}