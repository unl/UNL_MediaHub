<?php
class UNL_MediaHub_MediaList_Filter_Video implements UNL_MediaHub_Filter
{
    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->andWhere('m.type LIKE "video/%"');
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
