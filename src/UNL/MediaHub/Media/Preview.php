<?php
class UNL_MediaHub_Media_Preview implements UNL_MediaHub_MediaInterface
{

    public $options = array();

    public $url;

    function __construct($options = array())
    {
        if (!isset($options['url'])) {
            throw new Exception('URL to media is required', 500);
        }

        $this->options = $options + $this->options;

        $this->setURL($this->options['url']);

    }

    public function setURL($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new Exception('Invalid URL specified', 500);
        }

        $this->url = $url;
    }

    function isVideo()
    {
        return UNL_MediaHub::isVideo($this->url);
    }

    /**
     * Get the dimensions of the video
     * 
     * @return array|false array an array with 7 elements.
     *     Index 0 and 1 contains respectively the width and the height of the image.
     *     On failure, false is returned.
     */
    function getVideoDimensions()
    {
        return getimagesize($this->getThumbnailURL());
    }

    /**
     * Get the URL to the thumbnail for this image
     *
     * @return string
     */
    function getThumbnailURL()
    {
        return UNL_MediaHub_Controller::$thumbnail_generator.urlencode($this->url);
    }

    function getVideoTextTrackURL()
    {
        return 'http://www.universalsubtitles.org/api/1.0/subtitles/?video_url='.urlencode($this->url).'&sformat=srt';
    }
}