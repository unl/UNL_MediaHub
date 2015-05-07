<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class UNL_MediaHub_CacheInterface_UNLCacheLite extends Savvy_Turbo_CacheInterface_UNLCacheLite
{
    
    public $options = array('lifeTime'=>3600);
    
    public $group = 'Savvy_Turbo';

}
