<?php
/**
 * elements for the Yahoo Media RSS spec
 * http://search.yahoo.com/mrss/
 *
 */
class UNL_MediaYak_Feed_Media_NamespacedElements_mrss extends UNL_MediaYak_Feed_Media_NamespacedElements
{
    protected $xmlns = 'media';
    
    protected $uri = 'http://search.yahoo.com/mrss/';
    
    function getItemElements()
    {
        return array(
            'group',
            'content',
            'rating',
            'title',
            'description',
            'keywords',
            'thumbnail',
            'category',
            'player',
            'credit',
            'copyright',
            'text',
            'restriction',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaYak_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'media');
    }
}