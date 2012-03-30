<?php
/**
 * Template Definition for local.dwt
 */
require_once 'UNL/Templates.php';

class UNL_Templates_Version3x1_Local extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Local.tpl';                       // template name
    public $doctitle = "<title>Use a descriptive page title | Optional Site Title (use for context) | University of Nebraska&ndash;Lincoln</title>";                       // string()  
    public $head = "<!-- Place optional header elements here -->";                           // string()  
    public $titlegraphic = "The Title of My Site";                   // string()  
    public $breadcrumbs = "<ul> <li><a href=\"http://www.unl.edu/\" title=\"University of Nebraska&ndash;Lincoln\">UNL</a></li> <li class=\"selected\"><a href=\"#\" title=\"Site Title\">Site Title</a></li> <li>Page Title</li> </ul>";                    // string()  
    public $navlinks = "<!--#include virtual=\"../sharedcode/navigation.html\" -->";                       // string()  
    public $pagetitle = "<h1>This is your page title. It\'s now an &lt;h1&gt;, baby!</h1>";                      // string()  
    public $maincontentarea = "<h2>This is a blank page</h2> <p>Impress your audience with awesome content!</p>";                // string()  
    public $leftcollinks = "<!--#include virtual=\"../sharedcode/relatedLinks.html\" -->";                   // string()  
    public $contactinfo = "<!--#include virtual=\"../sharedcode/footerContactInfo.html\" -->";                    // string()  
    public $optionalfooter = "";                 // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version3x1_Local',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
