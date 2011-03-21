<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class Savvy_Turbo_CacheInterface_UNLCacheLite implements Savvy_Turbo_CacheInterface
{
    /**
     * UNL_Cache_Lite object
     * 
     * @var Cache_Lite
     */
    protected $cache;
    
    public $options = array('lifeTime'=>604800); // One week cache time
    
    public $group = 'Savvy_Turbo';
    
    /**
     * Constructor
     */
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->cache = new UNL_Cache_Lite($this->options);
    }
    
    /**
     * Get an item stored in the cache
     * 
     * @see Savvy_Turbo_CacheInterface#get()
     */
    function get($key)
    {
        return $this->cache->get($key, $this->group);
    }
    
    /**
     * Save an element to the cache
     * 
     * @see Savvy_Turbo_CacheInterface#save()
     */
    function save($data, $key)
    {
        return $this->cache->save($data, $key, $this->group);
    }

}

