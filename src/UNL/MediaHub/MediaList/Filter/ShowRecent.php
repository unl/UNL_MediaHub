<?php

class UNL_MediaHub_MediaList_Filter_ShowRecent implements UNL_MediaHub_NativeSqlFilter
{
    function apply(Doctrine_RawSql &$query) {}
    
    function getLabel()
    {
        return 'All Media';
    }
    
    function getType()
    {
        return 'browse';
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