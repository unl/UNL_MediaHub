<?php


class UNL_MediaYak_Media extends UNL_MediaYak_Models_BaseMedia
{
    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaYak_Media
     */
    static function getById($id)
    {
        return Doctrine::getTable('UNL_MediaYak_Media')->find($id);
    }
    
    /**
     * Get a piece of media by URL
     *
     * @param string $url URL to the video/audio file
     * 
     * @return UNL_MediaYak_Media
     */
    public static function getByURL($url)
    {
        $media = Doctrine::getTable('UNL_MediaYak_Media')->findOneByURL($url);
        if ($media) {
            return $media;
        }
        return false;
    }
    
    public function preInsert($event)
    {
        $this->setContentType();
    }
    
    public function preUpdate($event)
    {
        $this->setContentType();
    }
    
    function setContentType()
    {
        include_once 'Validate.php';
        $validate = new Validate();
        if (!$validate->uri($this->url)) {
            return false;
        }
        
        $headers = get_headers($this->url);
        if (count($headers)) {
            foreach($headers as $header) {
                if (strpos($header, 'Content-Type: ') !== false) {
                    $this->type = substr($header, 14);
                }
                if (strpos($header, 'Content-Length: ') !== false) {
                    $this->length = substr($header, 16);
                }
            }
        }
        return true;
    }
}

