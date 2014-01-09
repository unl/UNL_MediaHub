<?php
/**
 * Template Definition for local.dwt
 */
require_once 'UNL/Templates.php';

class UNL_Templates_Version4_Local extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Local.tpl';                       // template name
    public $doctitle = "<title>Use a descriptive page title | Optional Site Title (use for context) | University of Nebraska&ndash;Lincoln</title>";                       // string()  
    public $head = "<!-- Place optional header elements here -->";                           // string()  
    public $titlegraphic = "The Title of My Site";                   // string()  
    public $breadcrumbs = "<ul> <li><a href=\"http://www.unl.edu/\" title=\"University of Nebraska&ndash;Lincoln\" class=\"wdn-icon-home\">UNL</a></li> <li><a href=\"#\" title=\"Site Title\">Site Title</a></li> <li>Home</li> </ul>";                    // string()  
    public $navlinks = "<!--#include virtual=\"../sharedcode/navigation.html\" -->";                       // string()  
    public $pagetitle = "<h1>Please Title Your Page Here</h1>";                      // string()  
    public $maincontentarea = "<div class=\"wdn-band\"> <div class=\"wdn-inner-wrapper\"> <p>Impress your audience with awesome content!</p> </div> </div>";                // string()  
    public $optionalfooter = "";                 // string()  
    public $leftcollinks = "<!--#include virtual=\"../sharedcode/relatedLinks.html\" -->";                   // string()  
    public $contactinfo = "<!--#include virtual=\"../sharedcode/footerContactInfo.html\" -->";                    // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    public $__params = array (
  'class' => 
  array (
    'name' => 'class',
    'type' => 'text',
    'value' => '',
  ),
);

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version4_Local',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
