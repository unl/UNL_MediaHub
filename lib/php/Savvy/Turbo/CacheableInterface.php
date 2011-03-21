<?php
/**
 * Interface cacheable objects must implement.
 * 
 * @author bbieber
 */
interface Savvy_Turbo_CacheableInterface
{
    public function getCacheKey();
    public function run();
    public function preRun($cached);
}
