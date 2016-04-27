<?php
class UNL_MediaHub_FeedList_Filter_WithTitle implements UNL_MediaHub_Filter
{
    
    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('f.title IS NOT NULL AND f.title != ""');
    }
    
    function getLabel()
    {
        return 'Available Channels';
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