<?php
class UNL_MediaHub_User_Subscriptions extends UNL_MediaHub_SubscriptionList
{
    function __construct($options = array())
    {
        $user = UNL_MediaHub_Manager::getUser();
        $options['filter'] = new UNL_MediaHub_SubscriptionList_Filter_ByUser($user);
        parent::__construct($options);
    }
}