<?php
class UNL_MediaHub_MediaTextTrackList_Filter_ByMedia implements UNL_MediaHub_Filter
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('(tt.media_id = ?)', array($this->query));
    }

    public function getLabel()
    {
        return '';
    }

    public function getType()
    {
        return '';
    }

    public function getValue()
    {
        return '';
    }

    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return '';
    }
}
