<?php
class UNL_MediaHub_Feed_UserList extends UNL_MediaHub_UserList
{
    function __construct($options = array())
    {
        if (!isset($options['feed_id'])) {
            throw new UNL_MediaHub_RuntimeException('feed_id is required');
        }

        $options['feed']   = UNL_MediaHub_Feed::getById($options['feed_id']);
        if (empty($options['feed'])) {
            throw new UNL_MediaHub_RuntimeException('The feed is not found.', 404);
        }

        $options['filter'] = new UNL_MediaHub_UserList_Filter_ByFeed($options['feed']);

        parent::__construct($options);
    }
}
