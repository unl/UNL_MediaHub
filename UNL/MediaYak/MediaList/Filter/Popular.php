<?php

class UNL_MediaYak_MediaList_Filter_Popular implements UNL_MediaYak_Filter
{
    function getCacheKey()
    {
        return 'popular';
    }
    
    function apply(Doctrine_Query &$query)
    {
        if (file_exists(dirname(__FILE__).'/../../../../scripts/popular.txt')) {
            $popular = file(dirname(__FILE__).'/../../../../scripts/popular.txt');
        }
        $where = 'm.id = 0 ||';
        foreach ($popular as $media) {
            if (preg_match('/media\/([\d]+)/', $media, $matches)) {
                $where .= 'm.id = '.$matches[1].' || ';
            }
        }
        $where = trim($where, ' |');
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
}