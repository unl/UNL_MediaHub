<?php
class UNL_MediaHub_Manager_Exception implements UNL_MediaHub_CacheableInterface
{
    public $options = array();
    public $exception;

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        if (isset($this->options['exception']) && $this->options['exception'] instanceof Exception) {
            $this->exception = $this->options['exception'];
        } else {
            $this->exception = new UNL_MediaHub_RuntimeException('A unknown error occurred.');
        }
    }

    function preRun($cached)
    {

    }

    function getCacheKey()
    {
        return 'manager_execption';
    }

    function run()
    {
        $options = $this->options;
    }

    public function getFeeds($options = array())
    {
        //Show all feeds
        $options['limit'] = 99;

        return new UNL_MediaHub_User_FeedList($options);
    }

    public function getUploader()
    {
        return new UNL_MediaHub_Feed_Media_FileUpload();
    }
}
