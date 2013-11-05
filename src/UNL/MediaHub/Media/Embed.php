<?php
class UNL_MediaHub_Media_Embed
{
    public $media;

    function __construct(UNL_MediaHub_Media $media = null)
    {
        $this->media = $media;
    }

    /**
     * Get by ID
     *
     * @param int $id The id of the media to get
     *
     * @return UNL_MediaHub_Media_Embed
     */
    static function getById($id = null)
    {
        if (!$id) {
            return new self();
        }
        
        return new self(Doctrine::getTable('UNL_Mediahub_Media')->find($id));
    }
}
