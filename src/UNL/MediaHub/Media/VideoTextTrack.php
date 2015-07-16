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
    
    public function __construct($options, $format = 'srt')
    {
        if (!isset($options['id'])) {
            throw new Exception('Unknown media', 404);
        }

        $this->format = $format;
        $api = new UNL_MediaHub_AmaraAPI();
        
        if (isset($options['amara_id'],$options['lang_code'])) {
            //Skip database calls and get the track direct
            if (!$this->track = $api->getTextTrackByMediaID($options['amara_id'], $options['lang_code'], $format)) {
                throw new Exception('No Text track found for this media', 404);
            }
        } else {
            //Find the default language (db calls and multiple api calls)
            if (!$this->media = UNL_MediaHub_Media::getById($options['id'])) {
                throw new Exception('Unknown media', 404);
            }

            if (!$this->track = $api->getTextTrackByMediaURL($this->media->url, $format)) {
                throw new Exception('No Text track found for this media', 404);
            }
        }
    }
    
    public function getTextTrack()
    {
        return $this->track;
    }
}