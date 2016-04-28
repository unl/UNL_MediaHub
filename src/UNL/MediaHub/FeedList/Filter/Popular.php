<?php
class UNL_MediaHub_FeedList_Filter_Popular implements UNL_MediaHub_Filter
{
    
    public function apply(Doctrine_Query_Abstract $query)
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
    
    public function getLabel()
    {
        return 'Featured Channels';
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
}
