<?php
class UNL_MediaHub_FeedList_Filter_BySubscription implements UNL_MediaHub_Filter
{
    protected $subscription;
    
    public function __construct(UNL_MediaHub_Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('UNL_MediaHub_Subscription.id = ? AND UNL_MediaHub_Feed_Subscription.feed_id = f.id', $this->subscription->id);
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
