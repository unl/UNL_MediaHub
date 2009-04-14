<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class UNL_MediaYak_Cache implements UNL_MediaYak_CacheInterface
{
    /**
     * Cache_Lite object
     * 
     * @var Cache_Lite
     */
    protected $cache;
    
    /**
     * Constructor
     */
    function __construct()
    {
        include_once 'Cache/Lite.php';
        $this->cache = new Cache_Lite(array('lifeTime'=>null));
    }
    
    /**
     * Get an item stored in the cache
     * 
     * @see UNL/MediaYak/UNL_MediaYak_CacheInterface#get()
     */
    function get($key)
    {
        return $this->cache->get($key);
    }
    
    /**
     * Save an element to the cache
     * 
     * @see UNL/MediaYak/UNL_MediaYak_CacheInterface#save()
     */
    function save($data, $key)
    {
        return $this->cache->save($data, $key);
    }

}

?>