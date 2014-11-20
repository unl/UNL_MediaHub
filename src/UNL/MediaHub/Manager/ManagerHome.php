<?php
class UNL_MediaHub_Manager_ManagerHome implements UNL_MediaHub_CacheableInterface
{
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
        return 'manager_home';
    }

    function run()
    {
        $options = $this->options;
    }

    public function getFeeds($options = array())
    {
        return new UNL_MediaHub_User_FeedList($options);
    }
    
    public function getUploader()
    {
        return new UNL_MediaHub_Feed_Media_FileUpload();
    }
}