<?php

class UNL_MediaHub_Feed_Form
{
    public $action;
    
    public $feed;
    
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            $this->feed = UNL_MediaHub_Feed::getById($options['id']);
            $this->feed->loadReference('UNL_MediaHub_Feed_NamespacedElements_itunes');
            $this->feed->loadReference('UNL_MediaHub_Feed_NamespacedElements_media');
        }
    }
}
