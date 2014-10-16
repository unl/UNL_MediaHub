<?php
class UNL_MediaHub_FeedList_Filter_WithRelatedMedia implements UNL_MediaHub_Filter
{
    
function apply(Doctrine_Query &$query)
    {
        $query->select('f.*');
        $query->innerJoin('f.UNL_MediaHub_Media');
    }
    
    function getLabel()
    {
        return 'All Channels';
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