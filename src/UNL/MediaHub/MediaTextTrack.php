<?php

class UNL_MediaHub_MediaTextTrack extends UNL_MediaHub_Models_BaseMediaTextTrack
{
    const SOURCE_AMARA = 'amara';
    const SOURCE_REV = 'rev';

    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaHub_MediaTextTrack
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
    
    /**
     * called before an item is inserted to the database
     *
     * @param $event
     *
     * @return void
     */
    public function preInsert($event)
    {
        $this->datecreated = date('Y-m-d H:i:s');
    }

    /**
     * @return UNL_MediaHub_MediaTextTrackFileList
     */
    public function getFiles()
    {
        return new UNL_MediaHub_MediaTextTrackFileList(array('text_track_id'=>$this->id));
    }
}
