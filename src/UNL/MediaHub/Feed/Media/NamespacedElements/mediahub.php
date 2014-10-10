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

    /**
     * Static var which holds the custom item element names.
     * 
     * @var array Array of key=>description pairs
     */
    protected static $itemElements = array();

    function getItemElements()
    {
        return array_keys(self::$itemElements);
    }

    /**
     * Get the custom MediaHub item elements
     * 
     * @return array Array of key=>description pairs
     */
    public static function getCustomElements()
    {
        return self::$itemElements;
    }

    /**
     * Set the item elements placed under the mediahub namespace
     * 
     * @param array $elements Array of key=>description pairs
     */
    public static function setCustomElements($elements)
    {
        self::$itemElements = $elements;
    }

    public static function mediaHasElement($media_id, $element)
    {
        return parent::mediaHasElementNS($media_id, $element, self::$xmlns);
    }
}
