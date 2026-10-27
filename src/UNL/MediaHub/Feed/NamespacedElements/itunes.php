<?php

class UNL_MediaHub_Feed_NamespacedElements_itunes extends UNL_MediaHub_Feed_NamespacedElements
{
    protected $xmlns = 'itunes';
    
    protected $uri = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    
    function getChannelElements()
    {
        return array(
            'author',
            'block',
            'category',
            'image',
            'explicit',
            'keywords',
            'new-feed-url',
            'owner',
            'subtitle',
            'summary',
            );
    }
    
    function getItemElements()
    {
        return array(
            'author',
            'block',
            'duration',
            'explicit',
            'keywords',
            'subtitle',
            'summary',
            );
    }

    function preInsert($event)
    {
        $this->checkCategory();
        parent::preInsert($event);
    }

    function preUpdate($event)
    {
        $this->checkCategory();
        parent::preUpdate($event);
    }

    function checkCategory()
    {
        if ($this->element == 'category'
            && !empty($this->attributes)
            && is_array($this->attributes)) {
            $this->attributes = array('text'=>$this->attributes);
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