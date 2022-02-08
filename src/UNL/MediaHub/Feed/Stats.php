<?php
class UNL_MediaHub_Feed_Stats extends UNL_MediaHub_FeedAndMedia
{
    function __construct($options = array())
    {
        if (!isset($options['limit'])) {
            $options['limit'] = 100;
        }

        parent::__construct($options);
    }
}
