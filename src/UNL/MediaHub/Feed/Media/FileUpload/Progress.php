<?php
class UNL_MediaHub_Feed_Media_FileUpload_Progress
{
    public $options = array();

    /**
     * information about the file upload
     *
     * @var array
     */
    public $info = false;

    function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (empty($this->options['id'])) {
            throw new Exception('Upload ID must be passed to retieve progress info.', 404);
        }

        if (
            function_exists('apc_fetch')
            && ini_get('apc.enabled')
            && ini_get('apc.rfc1867')
            ) {
            $this->info = apc_fetch('upload_'.$this->options['id']);
        }

    }

    /**
     * Get the percentage complete
     *
     * @return boolean|number False on error
     */
    function getPercentComplete()
    {
        if (!$this->info) {
            return false;
        }

        if ($this->info['done']) {
            return 100;
        }

        if (!$this->info['total']) {
            return 0;
        }

        return $this->info['current'] / $this->info['total'] * 100;
    }
}