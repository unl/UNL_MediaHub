<?php
/**
 * Template Definition for mobile.dwt
 * 
 * PHP version 5
 *  
 * @category  Templates
 * @package   UNL_Templates
 * @author    Kyle Powers <kylepowers@gmail.com>
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
require_once 'UNL/Templates.php';

class UNL_Templates_Version3_Mobile extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Mobile.tpl';                      // template name
    public $doctitle = "<title>UNL | Department | New Page</title>";                       // string()  
    public $head = "<!-- Place optional header elements here -->";                           // string()  
    public $breadcrumbs = "<ul> <li><a href=\"http://www.unl.edu/\" title=\"University of Nebraska&ndash;Lincoln\">UNL</a></li> <li>Department</li> </ul>";                    // string()  
    public $navlinks = "<!--#include virtual=\"../sharedcode/navigation.html\" -->";                       // string()  
    public $titlegraphic = "<h1>Department</h1>";                   // string()  
    public $pagetitle = "";                      // string()  
    public $maincontentarea = "<p>Place your content here.<br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://wdn.unl.edu//\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Check this page</a> </p>";                // string()  
    public $leftcollinks = "<!--#include virtual=\"../sharedcode/relatedLinks.html\" -->";                   // string()  
    public $contactinfo = "<!--#include virtual=\"../sharedcode/footerContactInfo.html\" -->";                    // string()  
    public $optionalfooter = "";                 // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version3_Mobile',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
