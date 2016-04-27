<?php

class UNL_MediaHub_UserList_Filter_ByFeed implements UNL_MediaHub_Filter
{
    protected $feed;
    
    function __construct(UNL_MediaHub_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->select('DISTINCT u.*');
        $query->from('UNL_MediaHub_User u, UNL_MediaHub_User_Permission up');
        $query->where('u.uid = up.user_uid AND up.feed_id = ?', $this->feed->id);
    }
    
    function getLabel()
    {
        return '';
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