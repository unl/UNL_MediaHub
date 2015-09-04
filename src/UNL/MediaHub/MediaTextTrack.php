<?php

class UNL_MediaHub_MediaTextTrack extends UNL_MediaHub_Models_BaseMediaTextTrack
{
    const SOURCE_AMARA = 'amara';
    const SOURCE_REV = 'rev';
    
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
