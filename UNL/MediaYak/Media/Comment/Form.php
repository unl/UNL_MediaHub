<?php
class UNL_MediaYak_Media_Comment_Form
{
    public $media;
    
    public $action;
    
    function __construct(UNL_News_Release $media = null)
    {
        if (!empty($media)) {
            $this->media = $media;
        }
    }
}
?>