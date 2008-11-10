<?php

class UNL_MediaYak_Cache implements UNL_MediaYak_CacheInterface
{
    protected $cache;
    
    function __construct()
    {
        include_once 'Cache/Lite.php';
        $this->cache = new Cache_Lite(array('lifeTime'=>null));
    }
    
    function get($key)
    {
        return $this->cache->get($key);
    }
    
    function save($data, $key)
    {
        return $this->cache->save($data, $key);
    }

}

?>