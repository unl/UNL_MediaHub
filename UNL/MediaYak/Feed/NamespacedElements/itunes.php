<?php

class UNL_MediaYak_Feed_NamespacedElements_itunes extends UNL_MediaYak_Feed_NamespacedElements
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
}