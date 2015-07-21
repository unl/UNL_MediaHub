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
        return 'homepage-' . $this->options['format'];
    }

    function run()
    {
        $options = array(
            'limit'   => 30,
            'orderby' => 'popular_play_count'
        );
        $this->top_media = new UNL_MediaHub_MediaList($options);
        $this->top_media->run();
    }
    
}