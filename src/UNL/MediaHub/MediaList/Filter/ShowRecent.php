<?php

class UNL_MediaHub_MediaList_Filter_ShowRecent implements UNL_MediaHub_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $query->where('m.datecreated < ? AND m.privacy = ?', array(date('Y-m-d H:i:s'), 'PUBLIC'));
    }
    
    function getLabel()
    {
        return 'Recent Media';
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
        return 'Find recently added media';
    }
}