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
     * @param array $options Associative array of options
     * 
     * @see UNL_MediaYak_MediaList
     * 
     * @return void
     */
    function __construct($options = array())
    {

        if (isset($options['feed'])) {
            $this->feed = $options['feed'];
        } elseif (!empty($options['feed_id'])) {
            $this->feed = UNL_MediaYak_Feed::getById($options['feed_id']);
        } elseif (!empty($options['title'])) {
            $this->feed = UNL_MediaYak_Feed::getByTitle($this->options['title']);
        }

        $this->media_list = new UNL_MediaYak_MediaList(array('filter'=>new UNL_MediaYak_MediaList_Filter_ByFeed($this->feed))+$options);
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