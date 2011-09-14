<?php
class UNL_MediaHub_Feed_Media_FileUpload_Progress
{
    protected $options = array();

    /**
     * information about the file upload
     *
     * @var array
     */
    public $info = false;

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        if (!empty($options['id'])
            && function_exists('uploadprogress_get_info')) {
            $this->info = uploadprogress_get_info($options['id']);
        }
    }
}