<?php
class UNL_MediaYak_FeedList_Filter_Popular implements UNL_MediaYak_Filter
{
    
    function apply(Doctrine_Query &$query)
    {
        $file = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/scripts/popular_channels.txt';
        if (file_exists($file)) {
            $popular = file($file);
        }
        $where = '';
        $params = array();
        foreach ($popular as $feed) {
            if (preg_match('/channels\/(.*)$/', $feed, $matches)) {
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $where .= 'f.id = ? OR ';
                    $params[] = $matches[1];
                } else {
                    $where .= 'f.title = ? OR ';
                    $params[] = urldecode($matches[1]);
                }
            }
        }
        $where = trim($where, ' OR');
        $query->where($where, $params);
    }
    
    function getLabel()
    {
        return 'Featured Channels';
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