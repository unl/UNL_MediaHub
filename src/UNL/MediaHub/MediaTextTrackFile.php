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
    
    public function getURL()
    {
        $text_track = $this->getTextTrack();
        
        return UNL_MediaHub_Controller::$url . 'media/'.$text_track->media_id.'/'.$this->format.'?text_file_id='.$this->id;
    }

    public function getSrtURL()
    {
        $text_track = $this->getTextTrack();

        //This endpoint should convert VTT to SRT on the fly
        return UNL_MediaHub_Controller::$url . 'media/'.$text_track->media_id.'/'.self::FORMAT_SRT.'?text_file_id='.$this->id;
    }

    /**
     * @return UNL_MediaHub_MediaTextTrack
     */
    public function getTextTrack()
    {
        return UNL_MediaHub_MediaTextTrack::getById($this->media_text_tracks_id);
    }
}
