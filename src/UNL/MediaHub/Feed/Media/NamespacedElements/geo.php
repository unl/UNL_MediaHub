<?php


class UNL_MediaHub_Feed_Media_NamespacedElements_geo extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'geo';
    
    public static $uri = 'http://www.w3.org/2003/01/geo/wgs84_pos#';
        
    function getItemElements()
    {
        return array(
            'lat',
            'long',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaHub_Feed_Media_NamespacedElements::mediaHasElementNS($media_id, $element, 'geo');
    }
}
