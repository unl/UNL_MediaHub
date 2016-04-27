<?php
class UNL_MediaHub_FeedList_Filter_BySubscription implements UNL_MediaHub_Filter
{
    protected $subscription;
    
    function __construct(UNL_MediaHub_Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
    
    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('UNL_MediaHub_Subscription.id = ? AND UNL_MediaHub_Feed_Subscription.feed_id = f.id', $this->subscription->id);
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