<?php

class UNL_MediaHub_Feed_Media_FileUpload
{
    /**
     * @var UNL_MediaHub_Feed_Media_FeedSelection
     */
    public $feed_selection;

    function __construct()
    {
        $this->feed_selection = new UNL_MediaHub_Feed_Media_FeedSelection(UNL_MediaHub_AuthService::getInstance()->getUser());
    }
}