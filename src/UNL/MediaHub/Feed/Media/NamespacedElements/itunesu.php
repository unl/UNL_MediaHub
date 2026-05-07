<?php


class UNL_MediaHub_Feed_Media_NamespacedElements_itunesu extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'itunesu';
    
    public static $uri = 'http://www.itunesu.com/feed';
        
    function getItemElements()
    {
        return array(
            'category',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return parent::mediaHasElementNS($media_id, $element, self::$xmlns);
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

    public function __serialize(): array
    {
        return parent::__serialize();
    }

    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);
    }
}
