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
        $this->setMRSSThumbnail();
        $this->setMRSSContent();
    }
    
    public function postSave()
    {
//        var_dump('postsave');
//        $this->setMRSSThumbnail();
//        $this->setMRSSContent();
    }
    
    /**
     * Sets the thumbnail media rss element.
     * 
     * @return true
     */
    function setMRSSThumbnail()
    {
        if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_mrss::mediaHasElement($this->id, 'thumbnail')) {
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
    
    function setMRSSContent()
    {
        list($width, $height) = getimagesize(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->url));
        if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_mrss::mediaHasElement($this->id, 'content')) {
            // all good
        } else {
            $element = new UNL_MediaYak_Feed_Media_NamespacedElements_mrss();
            $element->media_id = $this->id;
            $element->element = 'content';
        }
        $attributes = array('url'      => $this->url,
                            'fileSize' => $this->length,
                            'type'     => $this->type,
                            'width'    => $width,
                            'height'   => $height,
                            'lang'     => 'en');
        if (isset($element->attributes) && is_array($element->attributes)) {
            $attributes = array_merge($element->attributes, $attributes);
        }
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

