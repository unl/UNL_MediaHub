<?php
/**
 * Template Definition for unlframework.dwt
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
 * Unlframework template object
 * 
 * @package UNL_Templates
 *
 */
class UNL_Templates_Version2_Unlframework extends UNL_Templates 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__template = 'Unlframework.tpl';                // template name
    public $doctitle = "<title>UNL Redesign</title>";                       // string()  
    public $head = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/browsersniffers/ie.html\" --> <!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/comments/developersnote.html\" --> <!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/metanfavico/metanfavico.html\" -->";                           // string()  
    public $siteheader = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/siteheader/siteheader.shtml\" -->";                     // string()  
    public $breadcrumbs = "<!-- WDN: see glossary item \'breadcrumbs\' --> <ul> <li class=\"first\"><a href=\"http://www.unl.edu/\">UNL</a></li> <li>UNL Framework</li> </ul>";                    // string()  
    public $shelf = "<!--#include virtual=\"/ucomm/templatedependents/templatesharedcode/includes/shelf/shelf.shtml\" -->";                          // string()  
    public $collegenavigationlist = "";          // string()  
    public $titlegraphic = "<h1>Department</h1> <h2>Taglines - We Do The Heavy Lifting</h2>";                   // string()  
    public $leftcolcontent = "<div id=\"navigation\"> <h4 id=\"sec_nav\">Navigation</h4> <div id=\"navlinks\"> <!--#include virtual=\"../sharedcode/navigation.html\" --> </div> <div id=\"nav_end\"></div> <div class=\"image_small_short\" id=\"leftRandomPromo\"> <a href=\"#\" id=\"leftRandomPromoAnchor\"><img id=\"leftRandomPromoImage\" alt=\"\" src=\"/ucomm/templatedependents/templatecss/images/transpixel.gif\" /></a> <script type=\"text/javascript\" src=\"../sharedcode/leftRandomPromo.js\"></script> </div> <!-- WDN: see glossary item \'sidebar links\' --> <div id=\"leftcollinks\"> <!--#include virtual=\"../sharedcode/relatedLinks.html\" --> </div> </div> <!-- close navigation -->";                 // string()  
    public $maincolcontent = "<!-- optional main big content image --> <div id=\"maincontent\"> <p style=\"margin:20px; border:3px solid #CC0000;padding:10px; text-align:center\"> <strong>Delete this box and place your content here.</strong><br /> Remember to validate your pages before publishing! Sample layouts are available through the <a href=\"http://www.unl.edu/webdevnet/\">Web Developer Network</a>. <br /> <a href=\"http://validator.unl.edu/check/referer\">Click here to check Validation</a> </p> </div> <!-- close right-area -->";                 // string()  
    public $bigfooter = "<div id=\"footer\"> <div id=\"footer_floater\"> <div id=\"copyright\"> <!--#include virtual=\"../sharedcode/footer.html\" --> <span><a href=\"http://jigsaw.w3.org/css-validator/check/referer\">CSS</a> <a href=\"http://validator.unl.edu/check/referer\">W3C</a> <a href=\"http://www1.unl.edu/feeds/\">RSS</a> </span><a href=\"http://www.unl.edu/\" title=\"UNL Home\"><img src=\"/ucomm/templatedependents/templatecss/images/wordmark.png\" alt=\"UNL\'s wordmark\" id=\"wordmark\" /></a></div> </div> </div>";                      // string()  

    /* Static get */
    function staticGet($k,$v=NULL) { return UNL_DWT::staticGet('UNL_Templates_Version2_Unlframework',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
