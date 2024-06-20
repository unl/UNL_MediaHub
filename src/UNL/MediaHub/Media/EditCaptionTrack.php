<?php

class UNL_MediaHub_Media_EditCaptionTrack
{
    public $options = array();
    private $trackFiles = array();

    public $media;
    public $track;
    public $trackFile;

    public function __construct($options = array())
    {
        $this->options = $options;

        if (!isset($options['media_id'])) {
            throw new \Exception('You must pass a media ID');
        }

        if (!$this->media = UNL_MediaHub_Media::getById($options['media_id'])) {
            throw new \Exception('Could not find that media', 404);
        }

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$this->media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        if (!isset($options['track_id'])) {
            throw new \Exception('You must pass a track ID');
        }

        if (!$this->track = UNL_MediaHub_MediaTextTrack::getById($options['track_id'])) {
            throw new \Exception('Could not find that track', 404);
        }

        if (empty($this->track->media_text_tracks_source_id) && !$this->track->is_ai_generated()) {
            throw new \Exception('Track must be a copy to edit', 404);
        }

        $this->trackFiles = $this->track->getFiles()->items;
        $this->trackFile = isset($this->trackFiles[0]) ? $this->trackFiles[0] : NULL;
        if (empty($this->trackFile)) {
            throw new \Exception('Track missing track file');
        }
    }
}
