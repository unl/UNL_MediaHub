<?php
class UNL_MediaHub_MediaTextTrackList_Filter_ByMedia implements UNL_MediaHub_Filter
{
    protected $query;

    function __construct($query)
    {
        $this->query = $query;
    }

    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('(tt.media_id = ?)', array($this->query));
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
