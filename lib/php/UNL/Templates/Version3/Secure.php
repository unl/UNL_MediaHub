<?php
/**
 * Template Definition for secure.dwt
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
 * Template Definition for secure.dwt
 * 
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_Templates_Version3_Secure extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Secure.tpl';                      // template name
    public $doctitle = "<title>UNL | Department | New Page</title>";                       // string()  
    public $head = "<!-- Place optional header elements here -->";                           // string()  
    public $identitymanagement = "<a href=\"https://login.unl.edu/cas/logout\">Logout</a>";             // string()  
    public $breadcrumbs = "<ul> <li><a href=\"http://www.unl.edu/\" title=\"University of Nebraska&ndash;Lincoln\">UNL</a></li> <li>Department</li> </ul>";                    // string()  
    public $navlinks = "<!--#include virtual=\"../sharedcode/navigation.html\" -->";                       // string()  
    public $titlegraphic = "<h1>Department</h1>";                   // string()  
    public $pagetitle = "";                      // string()  
    public $maincontentarea = "<p>Place your content here.<br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://wdn.unl.edu//\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Check this page</a> </p>";                // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version3_Secure',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
