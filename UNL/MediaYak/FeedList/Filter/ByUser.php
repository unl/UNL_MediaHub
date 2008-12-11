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
        $query->where('u.uid > ?', $user->uid);
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