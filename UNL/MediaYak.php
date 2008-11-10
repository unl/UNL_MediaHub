<?php
class UNL_MediaYak
{
    public $dsn;
    
    function __construct($dsn)
    {
        
    }
    
    function addMedia(array $details)
    {
        $media = new UNL_MediaYak_Media();
        $media->fromArray($details);
        return $media->save();
    }
}