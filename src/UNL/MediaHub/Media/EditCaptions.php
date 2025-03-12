<?php

class UNL_MediaHub_Media_EditCaptions
{
    public $options = array();

    public $media;

    public function __construct($options = array())
    {
        $this->options = $options;

        if (!isset($options['id'])) {
            throw new \Exception('You must pass a media ID');
        }

        if (!$this->media = UNL_MediaHub_Media::getById($options['id'])) {
            throw new \Exception('Could not find that media', 404);
        }

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$this->media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
    }

    public function getTrackHistory()
    {
        return new UNL_MediaHub_MediaTextTrackList(array('media_id'=>$this->media->id));
    }

    public function getRevOrderHistory()
    {
        return new UNL_MediaHub_RevOrderList(array('media_id'=>$this->media->id));
    }

    public function isTranscodingFinished()
    {
        $job = $this->media->getMostRecentTranscodingJob();
        return !($job && !$job->isFinished());
    }

    public function isTranscribingFinished()
    {
        $job = $this->media->getMostRecentTranscriptionJob();
        return $job && $job->isFinished();
    }

    public function isTranscribingError()
    {
        $job = $this->media->getMostRecentTranscriptionJob();
        return $job && $job->isError();
    }

    public function mediaHasCaptions()
    {
        return count($this->getTrackHistory()->items) > 0;
    }

    public function getEditCaptionsURL()
    {
        if ($this->isTranscodingFinished()) {
            $amara_api = new UNL_MediaHub_AmaraAPI();
            return $amara_api->getCaptionEditURL($this->media->url);
        }
        return false;
    }

    /**
     * Determine if any orders are currently pending
     *
     * @return bool
     */
    public function hasPendingOrder()
    {
        foreach ($this->getRevOrderHistory()->items as $order) {
            if ($order->isPending()) {
                return true;
            }
        }

        return false;
    }

    public function hasTranscriptionJob()
    {
        $job = $this->media->getMostRecentTranscriptionJob();
        return $job !== false;
    }
}
