<?php

class UNL_MediaHub_Feed_NamespacedElements_geo extends UNL_MediaHub_Feed_NamespacedElements
{
    protected $xmlns = 'geo';
    
    protected $uri = 'http://www.w3.org/2003/01/geo/wgs84_pos#';
    
    function getChannelElements()
    {
        return array(
            'lat',
            'long'
            );
    }
}