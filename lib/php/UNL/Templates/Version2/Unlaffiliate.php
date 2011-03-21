<?php
/**
 * Template Definition for unlaffiliate.dwt
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

class UNL_Templates_Version2_Unlaffiliate extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Unlaffiliate.tpl';                // template name
    public $doctitle = "<title>UNL Redesign</title>";                       // string()  
    public $head = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/ucomm/templatedependents/templatecss/layouts/affiliate.css\" />";                           // string()  
    public $siteheader = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/siteheader/affiliate.shtml\" -->";                     // string()  
    public $breadcrumbs = "<!-- WDN: see glossary item \'breadcrumbs\' --> <ul> <li class=\"first\"><a href=\"http://www.unl.edu/\">UNL</a></li> <li>UNL Framework</li> </ul>";                    // string()  
    public $shelf = "";                          // string()  
    public $titlegraphic = "<h1>Affiliate</h1> <h2>Taglines - We Do The Heavy Lifting</h2>";                   // string()  
    public $navlinks = "<!--#include virtual=\"../sharedcode/navigation.html\" -->";                       // string()  
    public $leftRandomPromo = "<div class=\"image_small_short\" id=\"leftRandomPromo\"> <a href=\"#\" id=\"leftRandomPromoAnchor\"><img id=\"leftRandomPromoImage\" alt=\"\" src=\"/ucomm/templatedependents/templatecss/images/transpixel.gif\" /></a> <script type=\"text/javascript\" src=\"../sharedcode/leftRandomPromo.js\"></script> </div>";                // string()  
    public $leftcollinks = "<!--#include virtual=\"../sharedcode/relatedLinks.html\" -->";                   // string()  
    public $maincontentarea = "<h2 class=\"sec_main\">This template is only for affiliates of UNL, or units that have been granted a marketing exemption from the university. Confirm your use of this template before using it!</h2> <p style=\"margin:20px; border:3px solid #CC0000;padding:10px; text-align:center\"> <strong>Delete this box and place your content here.</strong><br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://www.unl.edu/webdevnet/\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Click here to check Validation</a> </p> <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->";                // string()  
    public $optionalfooter = "";                 // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version2_Unlaffiliate',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
