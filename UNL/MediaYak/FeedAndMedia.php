<?php
class UNL_MediaYak_FeedAndMedia
{
    public $feed;
    
    public $media_list;
    
    function __construct(UNL_MediaYak_Feed $feed)
    {
        $this->feed = $feed;
        $this->media_list = new UNL_MediaYak_MediaList(new UNL_MediaYak_MediaList_Filter_ByFeed($feed));
    }
}

?>