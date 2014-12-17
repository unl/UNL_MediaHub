<?php
class UNL_MediaHub_Media_VideoTextTrack extends UNL_MediaHub_Media
{
    public static $amara_username = false;
    public static $amara_api_key  = false;
    
    /**
     * Get by ID
     *
     * @param int $id The id of the feed to get
     *
     * @return UNL_MediaHub_Media
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
}