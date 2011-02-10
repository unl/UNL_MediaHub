<?php
class UNL_MediaYak_Feed_Subscription extends UNL_MediaYak_Models_BaseFeedHasSubscription
{
    function process()
    {
        $feed         = UNL_MediaYak_Feed::getById($this->feed_id);
        $subscription = UNL_MediaYak_Subscription::getById($this->subscription_id);

        return $subscription->process($feed);
    }
}