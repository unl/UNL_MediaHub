<?php

class UNL_MediaHub_MediaTextTrackFile extends UNL_MediaHub_Models_BaseMediaTextTrackFile
{
    const KIND_CAPTION = 'caption';
    const KIND_SUBTITLE = 'subtitle';
    const KIND_DESCRIPTION = 'description';
    
    const FORMAT_SRT = 'srt';
    const FORMAT_VTT = 'vtt';
    
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
