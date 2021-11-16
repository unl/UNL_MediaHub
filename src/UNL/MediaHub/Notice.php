<?php
/**
 * Class which handles post data and media uploads
 * @author bbieber
 */
class UNL_MediaHub_Notice
{
    const TYPE_INFO = 'dcf-notice-info';
    const TYPE_SUCCESS = 'dcf-notice-success';
    const TYPE_WARNING = 'dcf-notice-warning';
    const TYPE_DANGER = 'dcf-notice-danger';
    
    public $type;
    public $title;
    public $body;
    
    public $links = array();
    
    public function __construct($title, $body, $type = '')
    {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
    }
    
    public function addLink($url, $text)
    {
        $this->links[$url] = $text;
    }
}
