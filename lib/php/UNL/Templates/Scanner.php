<?php
/**
 * This class will scan a template file for the regions, which you can use to 
 * analyze and use a rendered template file.
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
require_once 'UNL/DWT/Scanner.php';


class UNL_Templates_Scanner extends UNL_DWT_Scanner
{
    /**
     * Construct a remote file.
     *
     * @param string $html Contents of the page
     */
    function __construct($html)
    {
        parent::__construct($html);
    }
}

?>