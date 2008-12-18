<?php

class UNL_MediaYak_Feed_Form
{
    public $action;
    
    public $feed;
    
    function __construct(UNL_MediaYak_Feed $feed = null)
    {
        if (isset($feed)) {
            $this->feed = $feed;
        }
    }
}
