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
        $preload = '';
        if (isset($this->options['preload'])) {
            $preload = $this->options['preload'];
        }

        $autoplay = '';
        if (isset($this->options['autoplay'])) {
            $autoplay = $this->options['autoplay'];
        }
        
        return 'media_' . $this->media->id . '_' . $this->media->dateupdated 
            . '--format-' . $this->options['format'] 
            . '--autoplay-' . $autoplay 
            . '--preload-'. $preload;
    }
    
    public function run()
    {
        
    }
    
    public function preRun($cached)
    {
        
    }
}