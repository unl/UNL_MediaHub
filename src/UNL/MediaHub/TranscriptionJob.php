<?php

class UNL_MediaHub_TranscriptionJob extends UNL_MediaHub_Models_BaseTranscriptionJob
{
    const STATUS_SUBMITTED  = 'SUBMITTED'; //job created in mediahub
    const STATUS_WORKING = 'WORKING'; //job submitted to ai caption server
    const STATUS_ERROR = 'ERROR'; //error while converting to captions
    const STATUS_FINISHED = 'FINISHED'; //job finished

    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaHub_TranscriptionJob
     */
    public static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }

    public function preInsert($event)
    {
        $this->status = self::STATUS_SUBMITTED;
        
        if (empty($this->datecreated)) {
            $this->datecreated = date('Y-m-d H:i:s');
        }
    }

    /**
     * Determine if this order finished successfully
     *
     * @return bool
     */
    public function isSuccess()
    {
        return self::STATUS_FINISHED == $this->status;
    }
    
    public function isError()
    {
        return self::STATUS_ERROR == $this->status;
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

        return in_array($this->status, $completed_statuses);
    }


    /**
     * Determine if this media is still pending completion
     *
     * @return bool
     */
    public function isPending()
    {
        $pending_statuses = array(
            self::STATUS_SUBMITTED,
            self::STATUS_WORKING,
        );

        return in_array($this->status, $pending_statuses);
    }
}
