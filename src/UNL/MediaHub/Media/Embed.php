<?php
class UNL_MediaHub_Media_Embed
{
    public $media;
    public $version = 1;
    public $options = array();

    function __construct(UNL_MediaHub_Media $media = null, $version = 1, $options = array())
    {
        $this->media = $media;
        $this->version = $version;
        $this->options += $options;
    }

    /**
     * Get by ID
     *
     * @param int $id The id of the media to get
     *
     * @param int $version
     * @param array $options
     * @return UNL_MediaHub_Media_Embed
     */
    static function getById($id = null, $version = 1, $options = array())
    {
        if (!$id) {
            return new self();
        }
        
        return new self(Doctrine::getTable('UNL_Mediahub_Media')->find($id), $version, $options);
    }
}
