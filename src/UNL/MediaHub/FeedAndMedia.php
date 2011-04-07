<?php
/**
 * Abstract class for holding a feed and all related media
 * 
 * @author bbieber
 */
class UNL_MediaHub_FeedAndMedia
{
    /**
     * The Feed object
     * 
     * @var UNL_MediaHub_Feed
     */
    public $feed;
    
    /**
     * List of associated media.
     * 
     * @var UNL_MediaHub_MediaList
     */
    public $media_list;
    
    /**
     * Construct a Feed and Media object.
     * 
     * @param array $options Associative array of options
     * 
     * @see UNL_MediaHub_MediaList
     * 
     * @return void
     */
    function __construct($options = array())
    {

        if (!empty($options['feed'])) {
            $this->feed = $options['feed'];
        } elseif (!empty($options['feed_id'])) {
            $this->feed = UNL_MediaHub_Feed::getById($options['feed_id']);
        } elseif (!empty($options['title'])) {
            $this->feed = UNL_MediaHub_Feed::getByTitle($options['title']);
        }

        if (false === $this->feed) {
            throw new Exception('That feed could not be found.', 404);
        }

        $this->media_list = new UNL_MediaHub_MediaList(array('filter'=>new UNL_MediaHub_MediaList_Filter_ByFeed($this->feed))+$options);
    }
    
    /**
     * Set the media list
     * 
     * @param UNL_MediaHub_MediaList $media_list The list of media
     * 
     * @return void
     */
    public function setMediaList(UNL_MediaHub_MediaList $media_list)
    {
        $this->media_list = $media_list;
    }
}

?>