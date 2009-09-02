<?php
class UNL_MediaYak_FeedList_Filter_Popular implements UNL_MediaYak_Filter
{
    
    function apply(Doctrine_Query &$query)
    {
        if (file_exists(dirname(__FILE__).'/../../../../scripts/popular_feeds.txt')) {
            $popular = file(dirname(__FILE__).'/../../../../scripts/popular_feeds.txt');
        }
        $where = 'm.id = 0 ||';
        foreach ($popular as $feed) {
            if (preg_match('/channels\/([\d]+)/', $feed, $matches)) {
                $where .= 'f.id = '.$matches[1].' || ';
            }
        }
        $where = trim($where, ' |');
        $query->where($where);
    }
    
    function getLabel()
    {
        return 'Popular Channels';
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