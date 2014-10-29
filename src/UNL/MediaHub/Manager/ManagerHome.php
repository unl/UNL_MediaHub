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

}