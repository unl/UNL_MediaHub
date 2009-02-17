<?php

class UNL_MediaYak_HarvestedMedia
{
    protected $url;
    protected $title;
    protected $description;
    protected $datePublished;
    protected $namespacedElements = array();
    
    
    function __construct($url, $title, $description, $datePublished, $namespacedElements = null)
    {
        $this->url           = $url;
        $this->title         = $title;
        $this->description   = $description;
        $this->datePublished = $datePublished;
        if (count($namespacedElements)) {
            $this->namespacedElements = $namespacedElements;
        }
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
        return date('Y-m-d H:i', $this->datePublished);
    }
    
    function getNamespacedElements()
    {
        return $this->namespacedElements;
    }
}

?>