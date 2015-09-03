<?php

class UNL_MediaHub_MediaTextTrackFile extends UNL_MediaHub_Models_BaseMediaTextTrackFile
{
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
