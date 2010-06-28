<?php
/**
 * A Cache Service using UNL_Cache_Lite
 * 
 * PHP version 5
 *  
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Ned Hummel <nhummel2@unl.edu>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
require_once 'UNL/Templates/CachingService/CacheLite.php';
class UNL_Templates_CachingService_UNLCacheLite extends UNL_Templates_CachingService_CacheLite
{

    function __construct($options = array())
    {
        include_once 'UNL/Cache/Lite.php';
        $options = array_merge(array('lifeTime'=>3600), $options);
        $this->cache = new UNL_Cache_Lite($options);
    }

}
