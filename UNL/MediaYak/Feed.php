<?php
class UNL_MediaYak_Feed extends UNL_MediaYak_Models_BaseFeed
{
    
    static function getById($id)
    {
        return Doctrine::getTable('UNL_MediaYak_Feed')->find($id);
    }
    
    function addMedia(UNL_MediaYak_Media $media, UNL_MediaYak_Media_MetaData $metadata)
    {
    }
}
?>