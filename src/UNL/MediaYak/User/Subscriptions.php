<?php
class UNL_MediaYak_User_Subscriptions extends UNL_MediaYak_SubscriptionList
{
    function __construct($options = array())
    {
        $user = UNL_MediaYak_Manager::getUser();
        $options['filter'] = new UNL_MediaYak_SubscriptionList_Filter_ByUser($user);
        parent::__construct($options);
    }
}