<?php
class UNL_MediaHub_RevOrderList_Filter_ByMedia implements UNL_MediaHub_Filter
{
    protected $query;

    function __construct($query)
    {
        $this->query = $query;
    }

    function apply(Doctrine_Query &$query)
    {
        $query->where('(ro.media_id = ?)', array($this->query));
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
