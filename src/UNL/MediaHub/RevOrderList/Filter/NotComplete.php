<?php
class UNL_MediaHub_RevOrderList_Filter_NotComplete implements UNL_MediaHub_Filter
{
    function __construct()
    {
        
    }

    function apply(Doctrine_Query &$query)
    {
        $query->where('(ro.status NOT IN ("' . UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE . '", "' . UNL_MediaHub_RevOrder::STATUS_ERROR . '") AND ro.media_id IS NOT NULL)');
    }

    function getLabel()
    {
        return '';
    }

    function getType()
    {
        return '';
    }

    function getValue()
    {
        return '';
    }

    function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return '';
    }
}
