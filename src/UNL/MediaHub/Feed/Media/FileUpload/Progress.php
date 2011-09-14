<?php
class UNL_MediaHub_Feed_Media_FileUpload_Progress
{
    protected $options = array();

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
    }
}