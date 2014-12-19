<?php
class UNL_MediaHub_Media_VideoTextTrack
{
    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    /**
     * The format of the text track
     * 
     * @var
     */
    public $format;
    
    protected $track = false;
    
    public function __construct($media_id, $format = 'srt')
    {
        if (!$this->media = UNL_MediaHub_Media::getById($media_id)) {
            throw new Exception('Unknown media', 404);
        }
        
        $this->format = $format;
        
        $api = new UNL_MediaHub_AmaraAPI();
        
        if (!$this->track = $api->getTextTrack($this->media->url, $format)) {
            throw new Exception('No Text track found for this media', 404);
        }
    }
    
    public function getTextTrack()
    {
        return $this->track;
    }
}