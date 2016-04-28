<?php
class UNL_MediaHub_MediaList_Filter_NoContentType implements UNL_MediaHub_Filter
{
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('m.type IS NULL OR m.type=\'\' OR m.length = 0 OR m.length IS NULL');
    }
    
    public function getLabel()
    {
        return 'Missing Content Types';
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
        return 'Find media that has no content type defined';
    }
}
