<?php
class UNL_MediaYak_FeedList_Filter_BySubscription implements UNL_MediaYak_Filter
{
    protected $subscription;
    
    function __construct(UNL_MediaYak_Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaYak_Subscription.id = ? AND UNL_MediaYak_Feed_Subscription.feed_id = f.id', $this->subscription->id);
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