<?php
class UNL_MediaHub_SubscriptionList_Filter_ByUser implements UNL_MediaHub_Filter
{
    protected $user;
    
    public function __construct(UNL_MediaHub_User $user)
    {
        $this->user = $user;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('s.uidcreated = ?', $this->user->uid);
        $query->distinct();
    }
    
    public function getLabel()
    {
        return '';
    }
    
    public function getType()
    {
        return '';
    }
    
    public function getValue()
    {
        return '';
    }
    
    public function __toString()
    {
        return '';
    }
}
