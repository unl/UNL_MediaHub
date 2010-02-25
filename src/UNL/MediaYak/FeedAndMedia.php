<?php
/**
 * Abstract class for holding a feed and all related media
 * 
 * @author bbieber
 */
class UNL_MediaYak_FeedAndMedia
{
    /**
     * The Feed object
     * 
     * @var UNL_MediaYak_Feed
     */
    public $feed;
    
    /**
     * List of associated media.
     * 
     * @var UNL_MediaYak_MediaList
     */
    public $media_list;
    
    /**
     * Construct a Feed and Media object.
     * 
     * @param UNL_MediaYak_Feed $feed The feed
     * 
     * @see UNL_MediaYak_MediaList
     * 
     * @return void
     */
    function __construct(UNL_MediaYak_Feed $feed)
    {
        $this->feed = $feed;
        $this->media_list = new UNL_MediaYak_MediaList(new UNL_MediaYak_MediaList_Filter_ByFeed($feed));
    }
    
    /**
     * Set the media list
     * 
     * @param UNL_MediaYak_MediaList $media_list The list of media
     * 
     * @return void
     */
    public function setMediaList(UNL_MediaYak_MediaList $media_list)
    {
        $this->media_list = $media_list;
    }
}

?>