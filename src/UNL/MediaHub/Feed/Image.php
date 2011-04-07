<?php
class UNL_MediaYak_Feed_Image extends UNL_MediaYak_Feed
{
    /**
     * Get by ID
     *
     * @param int $id The id of the feed to get
     *
     * @return UNL_MediaYak_Feed
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }

    static function getByTitle($title)
    {
        return Doctrine::getTable(__CLASS__)->findOneByTitle($title);
    }
}