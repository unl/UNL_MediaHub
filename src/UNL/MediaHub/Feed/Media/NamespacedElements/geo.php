<?php


class UNL_MediaHub_Feed_Media_NamespacedElements_geo extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    protected $xmlns = 'geo';
    
    protected $uri = 'http://www.w3.org/2003/01/geo/wgs84_pos#';
        
    function getItemElements()
    {
        return array(
            'lat',
            'long',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaHub_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'geo');
    }
}
