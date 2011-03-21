<?php
/**
 * Interface for a version of the template files.
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
interface UNL_Templates_Version
{ 
    function getConfig();
    function getTemplate($template);
    function makeIncludeReplacements($html);
}
?>