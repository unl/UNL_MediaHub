<?php
/**
 * Interface cacheable objects must implement.
 * 
 * @author bbieber
 */
interface UNL_MediaYak_CacheableInterface
{
    public function getCacheKey();
    public function run();
    public function preRun();
}

?>