<?php

class UNL_MediaHub_RevOrder extends UNL_MediaHub_Models_BaseRevOrder
{
    const STATUS_MEDIAHUB_CREATED  = 'mediahub_created';
    const STATUS_MEDIAHUB_COMPLETE = 'mediahub_complete';
    const STATUS_ERROR             = 'error';
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
     * @return UNL_MediaHub_Media
     */
    public function getMedia()
    {
        return UNL_MediaHub_Media::getById($this->media_id);
    }
}
