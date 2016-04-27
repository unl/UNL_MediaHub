<?php

class UNL_MediaHub_MediaList_Filter_ByFeed implements UNL_MediaHub_NativeSqlFilter
{
    protected $feed;
    protected $privacy = 'PUBLIC';

    /**
     * @param UNL_MediaHub_Feed $feed
     */
    function __construct(UNL_MediaHub_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    function apply(Doctrine_RawSql &$query)
    {
        $sql = 'm.UNL_MediaHub_Feed_Media.feed_id = ?';
        $params = array($this->feed->id);
        
        $query->where($sql, $params);
    }
    
    function getLabel()
    {
        return '';
    }
    
    function getType()
    {
        return 'feed';
    }
    
    function getValue()
    {
        return $this->feed;
    }
    
    function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find media added to a specific feed';
    }
}