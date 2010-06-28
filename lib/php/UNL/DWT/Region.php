<?php
/**
 * Object representing a Dreamweaver template region
 * 
 * @category  Templates
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */
class UNL_DWT_Region
{
    var $name;
    var $type = 'string';
    var $len;
    var $line;
    var $flags;
    var $value;
}
?>