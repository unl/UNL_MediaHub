<?php
UNL_Templates::$options['version'] = 3;
$page = UNL_Templates::factory('Fixed');
if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle = '<title>UNL | MediaHub | Manager</title>';
$page->titlegraphic = '<h1>UNL MediaHub Manager</h1><h2>Lights, Camera, Action</h2>';
$page->addStyleSheet(UNL_MediaHub_Controller::getURL().'templates/html/css/all.css');
$page->addStyleSheet(UNL_MediaHub_Manager::getURL().'templates/css/all_manager.css');
$page->leftRandomPromo = '';

$page->breadcrumbs = '
<ul>
    <li><a href="http://www.unl.edu/">UNL</a></li>
    <li>MediaHub</li>
</ul>';

$page->navlinks = $savvy->render(null, 'Navigation.tpl.php');

$page->maincontentarea = $savvy->render($context->output);
$page->footercontent = '&copy; '.date('Y').' University of Nebraska&ndash;Lincoln | Lincoln, NE 68588 | 402-472-7211 | <a href="http://www1.unl.edu/comments/" title="Click here to direct your comments and questions">comments?</a>';
echo $page;
