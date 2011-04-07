<?php
class UNL_MediaHub_MediaList_Filter_NoContentType implements UNL_MediaHub_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $query->where('m.type IS NULL OR m.type=\'\' OR m.length = 0 OR m.length IS NULL');
    }
    
    function getLabel()
    {
        return 'Missing Content Types';
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
        return 'Find media that has no content type defined';
    }
}
