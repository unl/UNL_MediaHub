<?php
class UNL_MediaHub_User_FeedList extends UNL_MediaHub_FeedList
{
    function __construct($options = array())
    {
        if (!isset($options['limit'])) {
            $options['limit'] = 10;
        }

        if (empty($options['filter'])) {
            $user = UNL_MediaHub_AuthService::getInstance()->getUser();
            $options['filter'] = new UNL_MediaHub_FeedList_Filter_ByUser($user);
        }
        parent::__construct($options);
    }
}