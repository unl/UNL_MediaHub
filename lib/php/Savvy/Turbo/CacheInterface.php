<?php
/**
 * Interface cacheable objects must implement
 * 
 * @author bbieber
 */
interface Savvy_Turbo_CacheInterface
{
    /**
     * Get an item from the cache
     * @param string $key They unique key for the cached item.
     * 
     * @return mixed
     */
    public function get($key);
    
    /**
     * Save an item to the cache.
     * 
     * @param mixed  $data The data to save to the cache.
     * @param string $key  Unique key for this data.
     * 
     * @return bool
     */
    public function save($data, $key);
}
