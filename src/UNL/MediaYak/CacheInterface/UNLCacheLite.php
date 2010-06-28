<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class UNL_MediaYak_CacheInterface_UNLCacheLite implements UNL_MediaYak_CacheInterface
{
    /**
     * Cache_Lite object
     * 
     * @var Cache_Lite
     */
    protected $cache;
    
    public $options = array('lifeTime'=>3600);
    
    /**
     * Constructor
     */
    function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
        include_once 'UNL/Cache/Lite.php';
        $this->cache = new UNL_Cache_Lite($this->options);
    }
    
    /**
     * Get an item stored in the cache
     * 
     * @see UNL/MediaYak/UNL_MediaYak_CacheInterface#get()
     */
    function get($key)
    {
        return $this->cache->get($key, 'mediayak');
    }
    
    /**
     * Save an element to the cache
     * 
     * @see UNL/MediaYak/UNL_MediaYak_CacheInterface#save()
     */
    function save($data, $key)
    {
        return $this->cache->save($data, $key, 'mediayak');
    }

}

?>