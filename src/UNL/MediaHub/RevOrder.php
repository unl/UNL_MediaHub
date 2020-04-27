<?php

class UNL_MediaHub_RevOrder extends UNL_MediaHub_Models_BaseRevOrder
{
    const STATUS_MEDIAHUB_CREATED  = 'mediahub_created';
    const STATUS_MEDIAHUB_COMPLETE = 'mediahub_complete';
    const STATUS_ERROR             = 'error';
    const STATUS_CANCELLED         = 'Cancelled';

    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaHub_RevOrder
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
        if (empty($this->datecreated)) {
            $this->datecreated = date('Y-m-d H:i:s');
        }
    }

    /**
     * @return UNL_MediaHub_Media
     */
    public function getMedia()
    {
        return UNL_MediaHub_Media::getById($this->media_id);
    }
    
    public function getDetailsURL()
    {
        return UNL_MediaHub_Manager::getURL() . '?view=captionorderdetails&id=' . $this->id;
    }

    /**
     * Determine if this order finished successfully
     * 
     * @return bool
     */
    public function isSuccess()
    {
        if (self::STATUS_MEDIAHUB_COMPLETE == $this->status) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if this order is finished (success, cancel or error)
     */
    public function isFinished()
    {
        $completed_statuses = array(
            self::STATUS_CANCELLED,
            self::STATUS_ERROR,
            self::STATUS_MEDIAHUB_COMPLETE
        );
        
        if (in_array($this->status, $completed_statuses)) {
            return true;
        }
    }


    /**
     * Determine if this media is still pending completion
     * 
     * @return bool
     */
    public function isPending()
    {
        return !$this->isFinished();
    }
}
