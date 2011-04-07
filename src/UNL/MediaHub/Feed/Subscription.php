<?php
class UNL_MediaHub_Feed_Subscription extends UNL_MediaHub_Models_BaseFeedHasSubscription
{
    function process()
    {
        $feed         = UNL_MediaHub_Feed::getById($this->feed_id);
        $subscription = UNL_MediaHub_Subscription::getById($this->subscription_id);

        return $subscription->process($feed);
    }
}