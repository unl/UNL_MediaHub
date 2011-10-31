<?php


class UNL_MediaHub_Feed_Media_NamespacedElements_boxee extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'boxee';
    
    public static $uri = 'http://boxee.tv/spec/rss/';
        
    function getItemElements()
    {
        return array(
            'alternative-link', // <boxee:alternative-link url="http://myvideo.com/video_720.mov" thumb="http://myvideo.com/images/720.png" label="Watch in 720p" />
            'image',            // http://myvideo.com/movie/thumbs/1435.png
            'user_agent',       // Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6
            'content_type',     // tv
            'view-count',       // 3265
            'episode',          // 3
            'season',           // 4
            'tv-show-title',    // Lost
            'release-date',     // 10-25-2006
            'release-year',     // 1979
            'imdb-id',          // tt0850964
            'plot',             //
            'plot-summary',     //
            'user-rating',      //
            'cast',             //
            'producers',        //
            'directors',        //
            'provider',         // netflix
            'provider-name',    // Netflix
            'provider-thumb',   // http://netflix.com/images/thumb.png
            'country-allow',    // US
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaHub_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'boxee');
    }
    
    function preInsert($event)
    {
        $this->setAlternativeLinkAttribute();
        return parent::preInsert($event);
    }
    
    function preUpdate($event)
    {
        $this->setAlternativeLinkAttribute();
        return parent::preUpdate($event);
    }
    
    function setAlternativeLinkAttribute()
    {
        if (!empty($this->attributes) && !is_array($this->attributes)) {
            $this->attributes = array('alternative:link'=>$this->attributes);
            unset($this->value);
        }
    }
}
