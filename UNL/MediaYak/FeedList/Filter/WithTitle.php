<?php
class UNL_MediaYak_FeedList_Filter_WithTitle implements UNL_MediaYak_Filter
{
    
    function apply(Doctrine_Query &$query)
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