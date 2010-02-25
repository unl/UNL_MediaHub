<?php


class UNL_MediaYak_Feed_Media_NamespacedElements_itunesu extends UNL_MediaYak_Feed_Media_NamespacedElements
{
    protected $xmlns = 'itunes';
    
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
    
}
