<?php
class UNL_MediaHub_DefaultHomepage implements UNL_MediaHub_CacheableInterface
{
    public $top_media;
    public $latest_media;
    public $featured_channels;
    
    public $options = array();
    
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
    }

    function preRun($cached)
    {
        
    }

    function getCacheKey()
    {
        return 'homepage';
    }

    function run()
    {
        $options = $this->options;
        
        $options['limit']   = 5;
        $this->latest_media = new UNL_MediaHub_MediaList($options);

        $options['filter'] = new UNL_MediaHub_MediaList_Filter_Popular();
        $this->top_media = new UNL_MediaHub_MediaList($options);

        $options['filter'] = new UNL_MediaHub_FeedList_Filter_Popular();
        $options['limit']  = 3;
        $this->featured_channels = new UNL_MediaHub_FeedList($options);
    }
    
}