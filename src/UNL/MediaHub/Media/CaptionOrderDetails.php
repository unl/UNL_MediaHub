<?php

class UNL_MediaHub_Media_CaptionOrderDetails
{
    public $options = array();

    /**
     * @var UNL_MediaHub_RevOrder
     */
    public $order;
    
    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    public function __construct($options = array())
    {
        $this->options = $options;

        if (!isset($options['id'])) {
            throw new \Exception('You must pass an order ID', 400);
        }

        if (!$this->order = UNL_MediaHub_RevOrder::getById($options['id'])) {
            throw new \Exception('Could not find that order', 404);
        }
        
        $this->media = UNL_MediaHub_Media::getById($this->order->media_id);

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$this->media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
    }
}
