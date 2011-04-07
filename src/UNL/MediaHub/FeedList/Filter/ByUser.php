<?php
class UNL_MediaHub_FeedList_Filter_ByUser implements UNL_MediaHub_Filter
{
    protected $user;
    
    function __construct(UNL_MediaHub_User $user)
    {
        $this->user = $user;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_User_Permission.user_uid = ? AND UNL_MediaHub_User_Permission.feed_id = f.id', $this->user->uid);
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