<?php
class UNL_MediaYak_Subscription_AvailableFilters extends GlobIterator
{
    function __construct($options = array())
    {
        parent::__construct(__DIR__ . '/../MediaList/Filter/*.php');
    }

    function current()
    {
        $filename = parent::current()->getFileName();
        $filter_class = 'UNL_MediaYak_MediaList_Filter_'.substr($filename, 0, -4);
        return $filter_class;
    }
}