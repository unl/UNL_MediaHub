<?php
/**
 * elements for the Yahoo Media RSS spec
 * http://search.yahoo.com/mrss/
 *
 */
class UNL_MediaHub_Feed_NamespacedElements_media extends UNL_MediaHub_Feed_NamespacedElements
{
    protected $xmlns = 'media';
    
    protected $uri = 'http://search.yahoo.com/mrss/';
    
    function getChannelElements()
    {
        return array(
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
}