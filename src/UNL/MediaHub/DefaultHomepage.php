<?php
class UNL_MediaHub_DefaultHomepage implements UNL_MediaHub_CacheableInterface
{
    public $top_media;
    
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
        if (empty($options['limit'])) {
            $options['limit']   = 3;
        }
        $options['orderby'] = 'popular_play_count';
        $this->top_media = new UNL_MediaHub_MediaList($options);
        $this->top_media->run();
    }
    
}