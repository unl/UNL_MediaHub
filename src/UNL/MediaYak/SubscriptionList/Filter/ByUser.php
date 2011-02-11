<?php
class UNL_MediaYak_SubscriptionList_Filter_ByUser implements UNL_MediaYak_Filter
{
    protected $user;
    
    function __construct(UNL_MediaYak_User $user)
    {
        $this->user = $user;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('s.uidcreated = ?', $this->user->uid);
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