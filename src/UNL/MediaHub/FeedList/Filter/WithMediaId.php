<?php
class UNL_MediaHub_FeedList_Filter_WithMediaId implements UNL_MediaHub_Filter
{
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('UNL_MediaHub_Feed_Media.media_id = '.(int)$this->id.' AND UNL_MediaHub_Feed_Media.feed_id = f.id');
        $query->distinct();
    }
    
    public function getLabel()
    {
        return 'Related Channels';
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
