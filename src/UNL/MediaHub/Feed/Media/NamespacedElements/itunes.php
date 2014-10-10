<?php

class UNL_MediaHub_Feed_Media_NamespacedElements_itunes extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'itunes';
    
    public static $uri = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
        
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
        return parent::mediaHasElementNS($media_id, $element, self::$xmlns);
    }
    
	public static function mediaSetElement($media_id, $element, $value)
    {
        return parent::mediaSetElementNS($media_id, $element, self::$xmlns, $value);
    }
}