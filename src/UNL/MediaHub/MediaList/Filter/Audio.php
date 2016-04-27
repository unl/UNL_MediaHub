<?php
class UNL_MediaHub_MediaList_Filter_Audio implements UNL_MediaHub_NativeSqlFilter
{
    function apply(Doctrine_RawSql &$query)
    {
        $query->andWhere('m.type LIKE "audio/%"');
    }

    function getLabel()
    {
        return 'Audio';
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
        return 'Find media that is audio';
    }
}
