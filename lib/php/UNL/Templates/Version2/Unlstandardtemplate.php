<?php
/**
 * Template Definition for unlstandardtemplate.dwt
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
 * Unlstandardtemplate object
 * 
 * @package UNL_Templates
 *
 */
class UNL_Templates_Version2_Unlstandardtemplate extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Unlstandardtemplate.tpl';         // template name
    public $doctitle = "<title>UNL Redesign</title>";                       // string()  
    public $head = "";                           // string()  
    public $siteheader = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/siteheader/siteheader.shtml\" -->";                     // string()  
    public $breadcrumbs = "<ul> <li class=\"first\"><a href=\"http://www.unl.edu/\">UNL</a></li> <li>UNL Standard Template</li> </ul>";                    // string()  
    public $shelf = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/shelf/shelf.shtml\" -->";                          // string()  
    public $collegenavigationlist = "";          // string()  
    public $titlegraphic = "<h1>Department</h1> <h2>Taglines - We Do The Heavy Lifting</h2>";                   // string()  
    public $navcontent = "<div id=\"navlinks\"> <!--#include virtual=\"../sharedcode/navigation.html\" --> </div>";                     // string()  
    public $leftRandomPromo = "<div class=\"image_small_short\" id=\"leftRandomPromo\"> <a href=\"#\" id=\"leftRandomPromoAnchor\"><img id=\"leftRandomPromoImage\" alt=\"\" src=\"/ucomm/templatedependents/templatecss/images/transpixel.gif\" /></a> <script type=\"text/javascript\" src=\"../sharedcode/leftRandomPromo.js\"></script> </div>";                // string()  
    public $leftcollinks = "<h3>Related Links</h3> <!--#include virtual=\"../sharedcode/relatedLinks.html\" -->";                   // string()  
    public $maincontent = "<p style=\"margin:20px; border:3px solid #CC0000;padding:10px; text-align:center\"> <strong>Delete this box and place your content here.</strong><br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://www.unl.edu/webdevnet/\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Click here to check Validation</a> </p>";                    // string()  
    public $optionalfooter = "";                 // string()  
    public $footercontent = "<!--#include virtual=\"../sharedcode/footer.html\" -->";                  // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version2_Unlstandardtemplate',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
