<?php

class UNL_MediaYak_Feed_Form
{
    public $action;
    
    public $feed;
    
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            $this->feed = UNL_MediaYak_Feed::getById($options['id']);
            $this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_itunes');
            $this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_media');
        }
    }
}
