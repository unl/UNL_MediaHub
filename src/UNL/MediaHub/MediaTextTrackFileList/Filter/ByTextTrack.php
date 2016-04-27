<?php
class UNL_MediaHub_MediaTextTrackFileList_Filter_ByTextTrack implements UNL_MediaHub_Filter
{
    protected $query;

    function __construct($query)
    {
        $this->query = $query;
    }

    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('(ttf.media_text_tracks_id = ?)', array($this->query));
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
