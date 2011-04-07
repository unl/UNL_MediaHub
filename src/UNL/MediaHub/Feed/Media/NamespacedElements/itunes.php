<?php

class UNL_MediaYak_Feed_Media_NamespacedElements_itunes extends UNL_MediaYak_Feed_Media_NamespacedElements
{
    protected $xmlns = 'itunes';
    
    protected $uri = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
        
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
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaYak_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'itunes');
    }
    
	public static function mediaSetElement($media_id, $element, $value)
    {
        return UNL_MediaYak_Feed_Media_NamespacedElements::mediaSetElement($media_id, $element, 'itunes', $value);
    }
}