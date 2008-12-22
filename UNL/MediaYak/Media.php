<?php


class UNL_MediaYak_Media extends UNL_MediaYak_Models_BaseMedia
{
    public static function getByURL($url)
    {
        $media = Doctrine::getTable('UNL_MediaYak_Media')->findOneByURL($url);
        if ($media) {
            return $media;
        }
        return false;
    }
}

