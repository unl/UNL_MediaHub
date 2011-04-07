<?php
class UNL_MediaHub_FeedList_Filter_WithRelatedMedia implements UNL_MediaHub_Filter
{
    
function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_Feed_Media.media_id IS NOT NULL AND UNL_MediaHub_Feed_Media.feed_id = f.id');
        $query->distinct();
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