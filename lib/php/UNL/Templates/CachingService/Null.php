<?php
/**
 * A Null cache service that can be used for testing
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
require_once 'UNL/Templates/CachingService.php';
class UNL_Templates_CachingService_Null implements UNL_Templates_CachingService
{
    
    function clean($object = null)
    {
        return true;
    }
    
    function save($data, $key)
    {
        return true;
    }
    
    function get($key)
    {
        return false;
    }
}