<?php
class UNL_MediaYak_Feed_Image extends UNL_MediaYak_Feed
{
    static function getByTitle($title)
    {
        return Doctrine::getTable(__CLASS__)->findOneByTitle($title);
    }
}