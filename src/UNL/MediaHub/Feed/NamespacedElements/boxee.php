<?php

class UNL_MediaHub_Feed_NamespacedElements_boxee extends UNL_MediaHub_Feed_NamespacedElements
{
    protected $xmlns = 'boxee';
    
    protected $uri = 'as http://boxee.tv/spec/rss/';
    
    function getChannelElements()
    {
        return array(
            'expiry',           // 60
            'background-image', // 1280x720 image, http://www.wallpapers.net/wallpapers/snow_leopard-1280x720.jpg
            'interval',         // <boxee:interval val="60" />
            'category',         // <boxee:category name="Video" />
            );
    }
}