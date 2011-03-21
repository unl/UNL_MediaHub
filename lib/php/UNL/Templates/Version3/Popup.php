<?php
/**
 * Template Definition for popup.dwt
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
require_once 'UNL/Templates.php';

/**
 * Template Definition for popup.dwt
 * 
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_Templates_Version3_Popup extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Popup.tpl';                       // template name
    public $doctitle = "<title>UNL | Department | New Page</title>";                       // string()  
    public $head = "<!-- Place optional header elements here -->";                           // string()  
    public $titlegraphic = "<h1>Department</h1>";                   // string()  
    public $pagetitle = "";                      // string()  
    public $maincontentarea = "<p>Place your content here.<br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://wdn.unl.edu//\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Check this page</a> </p>";                // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version3_Popup',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
