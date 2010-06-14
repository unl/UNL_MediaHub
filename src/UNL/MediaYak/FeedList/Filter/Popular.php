<?php
class UNL_MediaYak_FeedList_Filter_Popular implements UNL_MediaYak_Filter
{
    
    function apply(Doctrine_Query &$query)
    {
        $file = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/scripts/popular_feeds.txt';
        if (file_exists($file)) {
            $popular = file($file);
        }
        $where = '';
        foreach ($popular as $feed) {
            if (preg_match('/channels\/([\d]+)/', $feed, $matches)) {
                $where .= 'f.id = '.$matches[1].' OR ';
            }
        }
        $where = trim($where, ' OR');
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