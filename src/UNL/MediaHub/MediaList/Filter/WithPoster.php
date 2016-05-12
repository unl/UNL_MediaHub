<?php
class UNL_MediaHub_MediaList_Filter_WithPoster implements UNL_MediaHub_Filter
{
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('m.poster IS NOT NULL AND m.poster != ""');
    }

    public function getLabel()
    {
        return 'Media with posters';
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
        return 'Find media with custom poster images defined';
    }
}
