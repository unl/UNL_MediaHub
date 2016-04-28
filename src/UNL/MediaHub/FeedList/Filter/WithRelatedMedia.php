<?php
class UNL_MediaHub_FeedList_Filter_WithRelatedMedia implements UNL_MediaHub_Filter
{
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->select('f.*');
        $query->innerJoin('f.UNL_MediaHub_Media');
    }
    
    public function getLabel()
    {
        return 'All Channels';
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
