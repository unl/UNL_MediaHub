<?php


class UNL_MediaYak_Feed_Media_NamespacedElements_itunesu extends UNL_MediaYak_Feed_Media_NamespacedElements
{
    protected $xmlns = 'itunesu';
    
    protected $uri = 'http://www.itunesu.com/feed';
        
    function getItemElements()
    {
        return array(
            'category',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaYak_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'itunesu');
    }
    
    function preInsert($event)
    {
        $this->setCategoryCodeAttribute();
        return parent::preInsert($event);
    }
    
    function preUpdate($event)
    {
        $this->setCategoryCodeAttribute();
        return parent::preUpdate($event);
    }
    
    function setCategoryCodeAttribute()
    {
        if (!empty($this->attributes) && !is_array($this->attributes)) {
            $this->attributes = array('itunesu:code'=>$this->attributes);
            unset($this->value);
        }
    }
}
