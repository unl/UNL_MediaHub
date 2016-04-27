<?php
class UNL_MediaHub_MediaList_Filter_WithPoster implements UNL_MediaHub_Filter
{
    function __construct()
    {
        
    }

    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('m.poster IS NOT NULL AND m.poster != ""');
    }

    function getLabel()
    {
        return 'Media with posters';
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
        return 'Find media with custom poster images defined';
    }
}
