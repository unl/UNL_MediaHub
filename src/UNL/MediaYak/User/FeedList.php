<?php
class UNL_MediaYak_User_FeedList extends UNL_MediaYak_FeedList
{
    function __construct($options = array())
    {
        if (!isset($options['limit'])) {
            $options['limit'] = null;
        }

        if (!isset($options['filter'])) {
            $user = UNL_MediaYak_Manager::getUser();
            $options['filter'] = new UNL_MediaYak_FeedList_Filter_ByUser($user);
        }
        parent::__construct($options);
    }
}