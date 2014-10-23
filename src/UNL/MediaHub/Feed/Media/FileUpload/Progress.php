<?php
class UNL_MediaHub_Feed_Media_FileUpload_Progress
{
    public $options = array();
    
    protected $percent_complete = 100;

    function __construct($options = array())
    {
        $this->options = $options + $this->options;

        $key = ini_get("session.upload_progress.prefix") . "media_upload";
        if (!empty($_SESSION[$key])) {
            $current = $_SESSION[$key]["bytes_processed"];
            $total = $_SESSION[$key]["content_length"];
            $this->percent_complete = $current < $total ? ceil($current / $total * 100) : 100;
        }
    }

    /**
     * Get the percentage complete
     *
     * @return boolean|number False on error
     */
    function getPercentComplete()
    {
        return $this->percent_complete;
    }
}