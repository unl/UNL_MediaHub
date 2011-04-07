<?php

class UNL_MediaHub_Harvester_YahooPipe extends UNL_MediaHub_Harvester
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
        $description = $this->feed['value']['items'][$this->current]['description'];
        
        $namespacedElements = array();
        
        foreach ($this->feed['value']['items'][$this->current] as $ns_element=>$value) {
            if (strpos($ns_element, ':') > 0) {
                list($xmlns, $element) = explode(':', $ns_element);
                if (!isset($namespacedElements[$xmlns])) {
                    $namespacedElements[$xmlns] = array();
                }
                $namespacedElements[$xmlns][$element] = $value;
            }
        }
        
        // @TODO Namespaced attributes need to be supported!
        return new UNL_MediaHub_HarvestedMedia($this->feed['value']['items'][$this->current]['link'],
                                               $title,
                                               $description,
                                               $pubDate,
                                               $namespacedElements);
    }
    
    
}

?>