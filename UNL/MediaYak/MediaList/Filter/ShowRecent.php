<?php

class UNL_MediaYak_MediaList_Filter_ShowRecent implements UNL_MediaYak_MediaList_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $query->where('m.datecreated > ?', date('Y-m-d H:i:s'));
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