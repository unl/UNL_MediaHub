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
        
        $muted = '';
        if (isset($this->options['muted'])) {
            $muted = $this->options['muted'];
        }

        $captions = '';
        if (isset($this->options['captions'])) {
            $captions = $this->options['captions'];
        }
        
        return 'media_' . $this->media->id . '_' . $this->media->dateupdated
            . '--format-' . $this->options['format']
            . '--autoplay-' . $autoplay
            . '--muted-' . $muted
            . '--preload-'. $preload
            . '--captions-'.$captions
            . '--version-'.$this->getPlayerVersion();
    }
    
    public function getPlayerVersion()
    {
        if ($this->options['format'] === 'iframe') {
            return 3;
        }
        
        if (!isset($this->options['version'])) {
            return 1;
        }
        
        return $this->options['version'];
    }
    
    public function run()
    {
        
    }
    
    public function preRun($cached)
    {
        
    }
}