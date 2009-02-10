<?php

$page = UNL_Templates::factory('Fixed');
if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle = '<title>UNL | Media Hub | Manager</title>';
$page->titlegraphic = '<h1>UNL MediaHub Manager</h1><h2>Lights, Camera, Action</h2>';
$page->addStyleSheet(UNL_MediaYak_Manager::getURL().'templates/css/all.css');
$page->addScript(UNL_MediaYak_Manager::getURL().'templates/scripts/jquery-1.3.1.min.js');
$page->leftRandomPromo = '';

$page->navlinks = '
<ul>
    <li><a href="'.UNL_MediaYak_Manager::getURL().'">My Channels</a></li>
</ul>';

$page->breadcrumbs = '
<ul>
    <li><a href="http://www.unl.edu/">UNL</a></li>
    <li>MediaHub</li>
</ul>';

$page->collegenavigationlist = '
<ul>
    <li>'.UNL_MediaYak_Manager::getUser()->uid.'</li>
    <li>Logout</li>
</ul>';
$page->maincontentarea = UNL_MediaYak_OutputController::display($this->output, true);
$page->footercontent = '&copy; '.date('Y').' University of Nebraska&ndash;Lincoln | Lincoln, NE 68588 | 402-472-7211 | <a href="http://www1.unl.edu/comments/" title="Click here to direct your comments and questions">comments?</a>';
echo $page;
