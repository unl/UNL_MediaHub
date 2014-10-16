<?php
/**
 * elements for the Yahoo Media RSS spec
 * http://search.yahoo.com/mrss/
 *
 */
class UNL_MediaHub_Feed_Media_NamespacedElements_media extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'media';
    
    public static $uri = 'http://search.yahoo.com/mrss/';
    
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
        return parent::mediaHasElementNS($media_id, $element, self::$xmlns);
    }
}