<?php
class UNL_MediaYak_MediaList_Filter_NoContentType implements UNL_MediaYak_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $query->where('m.type IS NULL OR m.type=\'\'');
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
}
?>