<?php
class UNL_MediaYak_FeedList_Filter_WithRelatedMedia implements UNL_MediaYak_Filter
{
    
function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaYak_Feed_Media.media_id IS NOT NULL AND UNL_MediaYak_Feed_Media.feed_id = f.id');
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