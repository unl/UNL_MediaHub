<?php
class UNL_MediaHub_MediaList_Filter_WithPoster implements UNL_MediaHub_NativeSqlFilter
{
    function __construct()
    {
        
    }

    function apply(Doctrine_RawSql &$query)
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
