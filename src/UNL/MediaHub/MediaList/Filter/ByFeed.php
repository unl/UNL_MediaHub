<?php

class UNL_MediaHub_MediaList_Filter_ByFeed implements UNL_MediaHub_Filter
{
    protected $feed;
    protected $privacy = 'PUBLIC';

    /**
     * @param UNL_MediaHub_Feed $feed
     * @param string $privacy  - One of PUBLIC, UNLISTED, PRIVATE, ALL
     */
    function __construct(UNL_MediaHub_Feed $feed, $privacy = 'PUBLIC')
    {
        $this->feed = $feed;
        $this->privacy = $privacy;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $sql = 'UNL_MediaHub_Feed_Media.feed_id = ? AND UNL_MediaHub_Feed_Media.media_id = m.id';
        $params = array($this->feed->id);
        
        if ($this->privacy != 'ALL') {
            $sql .= ' AND m.privacy = ?';
            $params[] = $this->privacy;
        }
        
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