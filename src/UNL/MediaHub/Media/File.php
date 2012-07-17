<?php

class UNL_MediaHub_Media_File extends UNL_MediaHub_Media
{
    /**
     * Get by ID
     *
     * @param int $id The id of the file to get
     *
     * @return UNL_MediaHub_Media
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
}