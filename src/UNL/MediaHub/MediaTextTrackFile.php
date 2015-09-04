<?php

class UNL_MediaHub_MediaTextTrackFile extends UNL_MediaHub_Models_BaseMediaTextTrackFile
{
    const KIND_CAPTION = 'caption';
    const KIND_SUBTITLE = 'subtitle';
    const KIND_DESCRIPTION = 'description';
    
    const FORMAT_SRT = 'srt';
    const FORMAT_VTT = 'vtt';

    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaHub_MediaTextTrackFile
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
}
