<?php
class UNL_MediaHub_CaptionReviewListManager {
    public $options = array();
    public $unreviewedMediaCaptionList;
    public $items = array();
    private $auth;
    private $user;
    public function __construct($options = array()) {
        $this->options = $options;
        $this->auth = UNL_MediaHub_AuthService::getInstance();
        $this->auth->login();
        $this->user = $this->auth->getUser();
        $this->unreviewedMediaCaptionList  = UNL_MediaHub_User::getMediaWithUnreviewedCaptions($this->user);
        $this->user = $this->auth->getUser();
    }


    public static function getURL() {
        return UNL_MediaHub_Controller::$url . "caption-review-list-manager";
    }

    public static function getJSONURL() {
        return UNL_MediaHub_Controller::$url . "caption-review-list-manager/?format=json";
    }

    public function hasUnreviewedCaptionMedia() {
        return count($this->unreviewedMediaCaptionList->items) > 0;
    }

    public function getUnreviewedCaptionMedia() {
        return $this->unreviewedMediaCaptionList->items;
    }
}
