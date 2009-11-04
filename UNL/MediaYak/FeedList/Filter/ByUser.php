<?php
class UNL_MediaYak_FeedList_Filter_ByUser implements UNL_MediaYak_Filter
{
    protected $user;
    
    function __construct(UNL_MediaYak_User $user)
    {
        $this->user = $user;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaYak_User_Permission.user_uid = ? AND UNL_MediaYak_User_Permission.feed_id = f.id', $this->user->uid);
        $query->distinct();
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