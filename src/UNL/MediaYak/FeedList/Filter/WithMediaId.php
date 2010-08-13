<?php
class UNL_MediaYak_FeedList_Filter_WithMediaId implements UNL_MediaYak_Filter
{
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaYak_Feed_Media.media_id = '.(int)$this->id.' AND UNL_MediaYak_Feed_Media.feed_id = f.id');
        $query->distinct();
    }
    
    function getLabel()
    {
        return 'With Specific Media';
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