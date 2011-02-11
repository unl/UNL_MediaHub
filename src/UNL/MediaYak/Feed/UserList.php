<?php
class UNL_MediaYak_Feed_UserList extends UNL_MediaYak_UserList
{
    function __construct($options = array())
    {
        if (!isset($options['feed_id'])) {
            throw new Exception('feed_id is required');
        }

        $options['feed']   = UNL_MediaYak_Feed::getById($options['feed_id']); 
        $options['filter'] = new UNL_MediaYak_UserList_Filter_ByFeed($options['feed']);

        parent::__construct($options);
    }
}