<?php

class UNL_MediaYak_MediaList_Filter_ByFeed implements UNL_MediaYak_Filter
{
    protected $feed;
    
    function __construct(UNL_MediaYak_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaYak_Feed_Media.feed_id = ? AND UNL_MediaYak_Feed_Media.media_id = m.id', $this->feed->id);
    }
    
    function getLabel()
    {
        return '';
    }
    
    function getType()
    {
        return '';
    }
    
    function getValue()
    {
        return '';
    }
    
    function __toString()
    {
        return '';
    }
}