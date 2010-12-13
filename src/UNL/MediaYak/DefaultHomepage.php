<?php
class UNL_MediaYak_DefaultHomepage implements UNL_MediaYak_CacheableInterface
{
    public $top_media;
    public $latest_media;
    public $featured_channels;
    
    public $options = array();
    
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
    }

    function preRun()
    {
        
    }

    function getCacheKey()
    {
        return 'homepage';
    }

    function run()
    {
        $options = $this->options;
        $options['filter'] = new UNL_MediaYak_MediaList_Filter_Popular();
        $this->top_media = new UNL_MediaYak_MediaList($options);

        $options['filter'] = new UNL_MediaYak_MediaList_Filter_ShowRecent();
        $this->latest_media = new UNL_MediaYak_MediaList($options);

        $options['filter'] = new UNL_MediaYak_FeedList_Filter_Popular();
        $this->featured_channels = new UNL_MediaYak_FeedList();
    }
    
}