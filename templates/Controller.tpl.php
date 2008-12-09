<?php
require_once 'UNL/Templates.php';

$page = UNL_Templates::factory('Fixed');
if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle     = '<title>UNL | Online Media</title>';
$page->titlegraphic = '<h1>UNL\'s Online Media</h1><h2></h2>';
$page->addStylesheet('/ucomm/templatedependents/templatecss/components/forms.css');
$page->breadcrumbs = '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li>Media</li></ul>';
$page->head .= '<script type="text/javascript">
var UNL_MediaYak = function()
{
    return {
        url : "'.UNL_MediaYak_Controller::getURL().'",
        thumbnail_generator : "'.UNL_MediaYak_Controller::$thumbnail_generator.'",
        getURL : function() {
            return UNL_MediaYak.url;
        }
    };
}();
var UNL_MediaYak_thumbnail_generator = "'.UNL_MediaYak_Controller::$thumbnail_generator.'";
</script>';
$page->addStyleDeclaration('blockquote {
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/quote.png) no-repeat top left;
    padding: 5px 5px 5px 48px;
    clear:both;
}
#comments ul li {
    list-style:none;
    margin-left:-28px;
    padding:10px;
    margin-bottom:20px;
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/fade.png) no-repeat top left #FFFFFF;
}
#comments ul li h4 {
    float:left;
}
#comments ul li em {
    float: right;
    font-size:.8em;
    color:#666666
}
.author, .addedDate {
    color:#999999;
    font-weight:bold;
}
h3 a {
    font-size:.7em;
}
#maincontent form fieldset {
    width:460px;
}
#maincontent form textarea {
    width:270px;
}
div.clr img {
    float:left;
    padding:6px 10px 16px 6px;
}
div.clr {
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/fade700.png) no-repeat top left #FFFFFF;
}
.content_holder {
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/content_holder_fade.jpg) repeat-x top #FFFFFF;
}
.content_holder img {
    margin-left: 15px;
}
blockquote {
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/quote.png) no-repeat top left;
    padding: 5px 5px 5px 48px;
    clear:both;
}
#maincontent ul li {
    list-style:none;
    margin-left:-28px;
    padding:10px;
    margin-bottom:10px;
    background:url('.UNL_MediaYak_Controller::getURL().'templates/images/fade700.png) no-repeat top left #FFFFFF;
}
.pager_links {
    font-weight:bold;
    margin:15px 0 15px 0;
}
.pager_links a {
    border: 1px solid #4194bd;
    padding:5px;
    color:#4194bd;
    background:none;
}
.pager_links a:hover {
    border:1px solid #bd4141;
    color:#bd4141;
}

');
$page->addScript('/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/swfobject.js');
$page->addScript(UNL_MediaYak_Controller::getURL().'templates/jquery-1.2.6.min.js');
$page->addScript(UNL_MediaYak_Controller::getURL().'templates/mediatools.js');
$page->addScript(UNL_MediaYak_Controller::getURL().'templates/audio-player/audio-player-noswfobject.js');
$page->maincontentarea = UNL_MediaYak_OutputController::display($this->output, true);
$page->navlinks        = '
<div id="local_search">
 <form action="'.UNL_MediaYak_Controller::getURL().'search/" id="localSearchForm" method="get">
  <div id="localSearchQuery">
   <label for="localSearchQuery-field" class="overlabel">Search UNL Media</label>
   <input id="localSearchQuery-field" type="text" name="q"  value="" />
  </div>
  <div id="localSearch">
    <input type="image" alt="Search" src="/ucomm/templatedependents/templatecss/images/transpixel.gif" id="localSearch-Button" name="localSearch-Button"/>
  </div>
 </form>
</div>

<ul>
    <li><a href="'.UNL_MediaYak_Controller::getURL().'">UNL Media</a></li>
    <li><a href="http://itunes.unl.edu/">UNL iTunes U</a></li>
</ul>';
$page->optionalfooter = '
<p>
    The UNL Office of University Communications maintains this database of online media.
    If there are additional functions that would be of interest to you, please
    <a href="http://www1.unl.edu/comments/">send us a comment</a>.
</p>';

$page->footercontent = '&copy; 2008 University of Nebraska&mdash;Lincoln | Lincoln, NE 68588 | 402-472-7211 | <a href="http://www1.unl.edu/comments/" title="Click here to direct your comments and questions">comments?</a>
<script type="text/javascript">
//<![CDATA[
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );
//]]>
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3203435-1");
pageTracker._initData();
pageTracker._setDomainName(".unl.edu");
pageTracker._trackPageview();
</script>
';
$page->leftRandomPromo = '';
echo $page;
