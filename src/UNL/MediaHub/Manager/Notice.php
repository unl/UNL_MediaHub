<?php
/**
 * Class which handles post data and media uploads
 * @author bbieber
 */
class UNL_MediaHub_Manager_Notice
{
    const TYPE_NOTICE = '';
    const TYPE_SUCCESS = 'affirm';
    const TYPE_ERROR = 'negate';
    const TYPE_ALERT = 'alert';
    
    public $type;
    public $title;
    public $body;
    
    public function __construct($title, $body, $type = '')
    {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
    }
}