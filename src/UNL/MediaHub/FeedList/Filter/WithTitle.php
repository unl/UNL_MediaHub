<?php
class UNL_MediaHub_FeedList_Filter_WithTitle implements UNL_MediaHub_Filter
{
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('f.title IS NOT NULL AND f.title != ""');
    }
    
    public function getLabel()
    {
        return 'Available Channels';
    }
    
    public function getType()
    {
        return '';
    }
    
    public function getValue()
    {
        return '';
    }
    
    public function __toString()
    {
        return '';
    }
}
