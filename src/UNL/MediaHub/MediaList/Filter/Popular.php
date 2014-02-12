<?php

class UNL_MediaHub_MediaList_Filter_Popular implements UNL_MediaHub_Filter
{
    function getCacheKey()
    {
        return 'popular';
    }
    
    function apply(Doctrine_Query &$query)
    {
        $popular = array();

        $file = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/scripts/popular.txt';
        if (file_exists($file)) {
            $popular = file($file);
        }

        $where = '';
        foreach ($popular as $media) {
            if (preg_match('/media\/([\d]+)/', $media, $matches)) {
                $where .= 'm.id = '.$matches[1].' OR ';
            }
        }
        $where = trim($where, ' OR');

        if (empty($where)) {
            $where = 'm.id = 0';
        }
        
        $where .= ' AND m.privacy = "PUBLIC"';

        $query->where($where);
    }
    
    function getLabel()
    {
        return 'Top Content';
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
        return 'Find popular media';
    }
}