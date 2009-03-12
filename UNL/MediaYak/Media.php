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
    
    public function postInsert($event)
    {
        $this->setThumbnail();
    }
    
    public function preUpdate($event)
    {
        $this->setContentType();
        $this->setThumbnail();
    }
    
    /**
     * Sets the thumbnail media rss element.
     * 
     * @return true
     */
    function setThumbnail()
    {
        if ($element = UNL_MediaYak_Feed_Media_NamespacedElements::mediaHasElement($media->id, 'thumbnail', 'mrss')) {
            // all ok
        } else {
            $element = new UNL_MediaYak_Feed_Media_NamespacedElements_mrss();
            $element->media_id = $this->id;
            $element->element = 'thumbnail';
        }
        $attributes = array('url' => UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->url),
                            //width="75" height="50" time="12:05:01.123"
                            );
        $element->attributes = $attributes;
        $element->save();
        return true;
    }
    
    /**
     * Sets the content type for the media being added.
     * 
     * @return bool
     */
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

