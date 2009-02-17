<?php

class UNL_MediaYak_Harvester_YahooPipe extends UNL_MediaYak_Harvester
{
    
    protected $feed;
    
    protected $current = 0;
    
    function __construct($serialized_pipe_url)
    {
        if ($feed = @file_get_contents($serialized_pipe_url)) {
            $feed = unserialize($feed);
            if ($feed) {
                // All ok
                $this->feed = $feed;
                return;
            }
        }
        throw new Exception('Bad yahoo pipe!');
    }
    
    function next()
    {
        $this->current++;
    }
    
    function valid()
    {
        if ($this->current < count($this->feed['value']['items'])) {
            return true;
        }
        return false;
    }
    
    function rewind()
    {
        $this->current = 0;
    }
    
    function key()
    {
        return $this->feed['value']['items'][$this->current]['link'];
    }
    
    function current()
    {
        if (isset($this->feed['value']['items'][$this->current]['y:published']['utime'])) {
            $pubDate = $this->feed['value']['items'][$this->current]['y:published']['utime'];
        } else {
            $pubDate = strtotime($this->feed['value']['items'][$this->current]['pubDate']);
        }
        
        $title = $this->feed['value']['items'][$this->current]['title'];
        
        if (isset($this->feed['value']['items'][$this->current]['itunes:subtitle'])
            && !empty($this->feed['value']['items'][$this->current]['itunes:subtitle'])) {
            $title = $this->feed['value']['items'][$this->current]['itunes:subtitle'];
        }
        
        $description = $this->feed['value']['items'][$this->current]['description'];
        
        if (isset($this->feed['value']['items'][$this->current]['itunes:summary'])
            && !empty($this->feed['value']['items'][$this->current]['itunes:summary'])) {
            $description = $this->feed['value']['items'][$this->current]['itunes:summary'];
        }
        
        return new UNL_MediaYak_HarvestedMedia($this->feed['value']['items'][$this->current]['link'],
                                               $title,
                                               $description,
                                               $pubDate);
    }
    
    
}

?>