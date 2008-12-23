<?php

class UNL_MediaYak_UserList_Filter_ByFeed implements UNL_MediaYak_Filter
{
    protected $feed;
    
    function __construct(UNL_MediaYak_Feed $feed)
    {
        $this->feed = $feed;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->select('DISTINCT u.*');
        $query->from('UNL_MediaYak_User u, UNL_MediaYak_User_Permission up');
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