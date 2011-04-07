<?php
class UNL_MediaHub_FeedList_Filter_WithMediaId implements UNL_MediaHub_Filter
{
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_Feed_Media.media_id = '.(int)$this->id.' AND UNL_MediaHub_Feed_Media.feed_id = f.id');
        $query->distinct();
    }
    
    function getLabel()
    {
        return 'Related Channels';
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