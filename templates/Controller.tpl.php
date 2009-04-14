<?php
require_once 'UNL/Templates.php';

$page = UNL_Templates::factory('Fixed');
if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle     = '<title>UNL | Media Hub</title>';
$page->titlegraphic = '<h1>UNL Media Hub</h1><h2>Lights, Camera, Action</h2>';
$page->addStyleSheet(UNL_MediaYak_Controller::getURL().'templates/css/all.css');
$page->breadcrumbs = '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li>Media</li></ul>';
$page->addScript('/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/swfobject.js');
$page->addScript(UNL_MediaYak_Controller::getURL().'templates/jquery-1.2.6.min.js');
$page->addScript(UNL_MediaYak_Controller::getURL().'templates/mediatools.js');
$page->head .= '<script type="text/javascript">
UNL_MediaYak.url  = "'.UNL_MediaYak_Controller::getURL().'";
UNL_MediaYak.thumbnail_generator = "'.UNL_MediaYak_Controller::$thumbnail_generator.'";
var UNL_MediaYak_thumbnail_generator = "'.UNL_MediaYak_Controller::$thumbnail_generator.'";
</script>';
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
    <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media Hub</a></li>
</ul>';

if (!UNL_MediaYak_Controller::isLoggedIn()) {
    $page->collegenavigationlist = '<ul><li><a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaYak_Controller::getURL()).'">Login</a></li></ul>';
} else {
    $page->collegenavigationlist = '<ul><li><a href="?logout">Logout</a></li></ul>';
}

$page->leftcollinks = '
<h3>Related Links</h3>
<ul>
    <li><a href="http://itunes.unl.edu/">UNL On iTunes U</a></li>
</ul>
';
$page->optionalfooter = '
<p>
    The UNL Office of University Communications maintains this database of online media.
    If there are additional functions that would be of interest to you, please
    <a href="http://www1.unl.edu/comments/">send us a comment</a>.
</p>';

$page->footercontent = '&copy; '.date('Y').' University of Nebraska&mdash;Lincoln | Lincoln, NE 68588 | 402-472-7211 | <a href="http://www1.unl.edu/comments/" title="Click here to direct your comments and questions">comments?</a>';
$page->leftRandomPromo = '';
echo $page;
