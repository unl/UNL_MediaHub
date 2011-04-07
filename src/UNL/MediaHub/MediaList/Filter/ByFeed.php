<?php

class UNL_MediaHub_MediaList_Filter_ByFeed implements UNL_MediaHub_Filter
{
    protected $feed;
    
    function __construct(UNL_MediaHub_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_Feed_Media.feed_id = ? AND UNL_MediaHub_Feed_Media.media_id = m.id', $this->feed->id);
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

    public static function getDescription()
    {
        return 'Find media added to a specific feed';
    }
}