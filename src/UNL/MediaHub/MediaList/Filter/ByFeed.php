<?php

class UNL_MediaHub_MediaList_Filter_ByFeed implements UNL_MediaHub_Filter
{
    protected $feed;
    protected $privacy = 'PUBLIC';

    /**
     * @param UNL_MediaHub_Feed $feed
     */
    public function __construct(UNL_MediaHub_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->addFrom('LEFT JOIN feed_has_media fm2 ON (fm2.media_id = m.id)');
        $sql = 'fm2.feed_id = ?';
        $params = array($this->feed->id);
        
        $query->andWhere($sql, $params);
    }
    
    public function getLabel()
    {
        return '';
    }
    
    public function getType()
    {
        return 'feed';
    }
    
    public function getValue()
    {
        return $this->feed;
    }
    
    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find media added to a specific feed';
    }
}
