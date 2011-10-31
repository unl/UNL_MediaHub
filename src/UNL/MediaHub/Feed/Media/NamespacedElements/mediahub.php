<?php
/**
 * mediahub namespace.  To house all xml information that does not fit well with an exisiting standard or is unique to mediahub.
 * 
 * @author mfairchild
 *
 */
class UNL_MediaHub_Feed_Media_NamespacedElements_mediahub extends UNL_MediaHub_Feed_Media_NamespacedElements
{
    public static $xmlns = 'mediahub';
    
    public static $uri = '';
    
    function getItemElements()
    {
        return array(
            'water_maf',
            'water_cfs',
            );
    }
    
    public static function mediaHasElement($media_id, $element)
    {
        return UNL_MediaHub_Feed_Media_NamespacedElements::mediaHasElement($media_id, $element, 'mediahub');
    }
}
