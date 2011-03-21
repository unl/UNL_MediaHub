<?php
class Savvy_Turbo extends Savvy
{
    /**
     * The caching interface.
     * 
     * @var Savvy_Turbo_CacheInterface
     */
    protected $cache;
    
    /**
     * Set the cache interface
     * 
     * @param Savvy_Turbo_CacheInterface $cache
     */
    public function setCacheInterface(Savvy_Turbo_CacheInterface $cache)
    {
        $this->cache = $cache;
    }
    
    /**
     * Get the cache interface
     * 
     * @return Savvy_Turbo_CacheInterface
     */
    public function getCacheInterface()
    {
        if (!isset($this->cache)) {
            $this->setCacheInterface(new Savvy_Turbo_CacheInterface_UNLCacheLite());
        }
        return $this->cache;
    }

    /**
     * Render an object, and if it is cacheable, cache the output.
     * 
     * @see lib/php/Savvy::renderObject()
     */
    public function renderObject($object, $template = null)
    {
        if ($object instanceof Savvy_Turbo_CacheableInterface
            || ($object                    instanceof Savvy_ObjectProxy
                && $object->getRawObject() instanceof Savvy_Turbo_CacheableInterface)) {
            $key = $object->getCacheKey();

            // We have a valid key to store the output of this object.
            if ($key !== false && $data = $this->getCacheInterface()->get($key)) {
                // Tell the object we have cached data and will output that.
                $object->preRun(true);
            } else {
                // Content should be cached, but none could be found.
                $object->preRun(false);
                $object->run();
                $data = parent::renderObject($object, $template);

                if ($key !== false) {
                    $this->getCacheInterface()->save($data, $key);
                }

            }

            if ($object instanceof Savvy_Turbo_PostRunReplacements) {
                $data = $object->postRun($data);
            }

            return $data;
        }

        return parent::renderObject($object, $template);

    }

}