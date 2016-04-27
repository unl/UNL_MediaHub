<?php
class UNL_MediaHub_SubscriptionList_Filter_ByUser implements UNL_MediaHub_Filter
{
    protected $user;
    
    function __construct(UNL_MediaHub_User $user)
    {
        $this->user = $user;
    }
    
    function apply(Doctrine_Query_Abstract &$query)
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