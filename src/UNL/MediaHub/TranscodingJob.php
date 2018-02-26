<?php

class UNL_MediaHub_TranscodingJob extends UNL_MediaHub_Models_BaseTranscodingJob
{
    const STATUS_SUBMITTED  = 'SUBMITTED'; //job created in mediahub
    const STATUS_WORKING = 'WORKING'; //job submitted to transcoding server
    const STATUS_ERROR = 'ERROR'; //error while transcoding
    const STATUS_FINISHED = 'FINISHED'; //job finished
    
    const JOB_TYPE_MP4 = 'mp4';
    const JOB_TYPE_HLS = 'hls';

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
        $this->status = self::STATUS_SUBMITTED;
        
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

    /**
     * Determine if this order finished successfully
     *
     * @return bool
     */
    public function isSuccess()
    {
        if (self::STATUS_FINISHED == $this->status) {
            return true;
        }

        return false;
    }

    /**
     * Determine if this order is finished
     */
    public function isFinished()
    {
        $completed_statuses = array(
            self::STATUS_FINISHED,
            self::STATUS_ERROR,
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
