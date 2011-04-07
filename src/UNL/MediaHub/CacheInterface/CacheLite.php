<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class UNL_MediaHub_CacheInterface_CacheLite extends Savvy_Turbo_CacheInterface_CacheLite
{
    
    public $options = array('lifeTime'=>3600);
    


}
