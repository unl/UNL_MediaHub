<?php
class UNL_MediaHub_MediaPlayer implements \Savvy_Turbo_CacheableInterface
{
    public $media;
    
    protected $options;
    
    function __construct(UNL_MediaHub_Media $media, $options)
    {
        $this->media   = $media;
        $this->options = $options;
    }
    
    public function getCacheKey()
    {
        return 'media_' . $this->media->id . '_' . $this->media->dateupdated . $this->options['format'];
    }
    
    public function run()
    {
        
    }
    
    public function preRun($cached)
    {
        
    }
}