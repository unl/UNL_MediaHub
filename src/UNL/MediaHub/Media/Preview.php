<?php
class UNL_MediaHub_Media_Preview
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
}