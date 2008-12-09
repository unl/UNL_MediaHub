<?php

class UNL_MediaYak_HarvestedMedia
{
    protected $url;
    protected $title;
    protected $description;
    protected $datePublished;
    
    function __construct($url, $title, $description, $datePublished)
    {
        $this->url           = $url;
        $this->title         = $title;
        $this->description   = $description;
        $this->datePublished = $datePublished;
    }
    
    function getURL()
    {
        return $this->url;
    }
    
    function getTitle()
    {
        return $this->title;
    }
    
    function getDescription()
    {
        return $this->description;
    }
    
    function getDatePublished()
    {
        return date('Y-m-d H:i', strtotime($this->datePublished));
    }
}

?>