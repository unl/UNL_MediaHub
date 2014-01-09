<?php
/**
 * Template Definition for unlaffiliate.dwt
 */
require_once 'UNL/Templates.php';

class UNL_Templates_Version4_Unlaffiliate extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Unlaffiliate.tpl';                // template name
    public $doctitle = "<title>Use a descriptive page title | Optional Site Title (use for context) | UNL Affiliate</title>";                       // string()  
    public $head = "<!-- Place optional header elements here --> <link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../sharedcode/affiliate.css\" /> <link href=\"../sharedcode/affiliate_imgs/favicon.ico\" rel=\"shortcut icon\" />";                           // string()  
    public $titlegraphic = "The Title of My Site";                   // string()  
    public $breadcrumbs = "<ul> <li><a href=\"http://www.throughtheeyes.org/\" title=\"Through the Eyes of the Child Initiative\">Home</a></li> </ul>";                    // string()  
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
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version4_Unlaffiliate',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
