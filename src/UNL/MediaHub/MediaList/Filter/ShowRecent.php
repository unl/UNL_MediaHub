<?php

class UNL_MediaHub_MediaList_Filter_ShowRecent implements UNL_MediaHub_Filter
{
    public function apply(Doctrine_Query_Abstract $query) {}
    
    public function getLabel()
    {
        return 'All Media';
    }
    
    public function getType()
    {
        return 'browse';
    }
    
    public function getValue()
    {
        return '';
    }
    
    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find recently added media';
    }
}
