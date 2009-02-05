<?php

class UNL_MediaYak_Feed_Form
{
    public $action;
    
    public $feed;
    
    function __construct(UNL_MediaYak_Feed $feed = null)
    {
        if (isset($feed)) {
            $this->feed = $feed;
            $this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_itunes');
            $this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_mrss');
        }
    }
}
