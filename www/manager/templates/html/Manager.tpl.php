<?php
UNL_Templates::$options['version'] = 4.0;
$page = UNL_Templates::factory('Fixed');
if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle     = '<title>Manager | MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = 'UNL MediaHub';
$page->pagetitle    = '';
$page->addStyleSheet(UNL_MediaHub_Controller::getURL().'templates/html/css/all.css');
$page->addStyleSheet(UNL_MediaHub_Manager::getURL().'templates/css/all_manager.css');
$page->head .= '<script>var baseurl = "'.UNL_MediaHub_Manager::getURL().'";</script>';
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/manager.js');
$page->leftRandomPromo = '';

$page->breadcrumbs = '
<ul>
    <li><a href="http://www.unl.edu/">UNL</a></li>
    <li><a href="' . UNL_MediaHub_Controller::getURL() .'">MediaHub</a></li>
    <li>Manage Media</li>
</ul>';

$page->navlinks = $savvy->render(null, 'Navigation.tpl.php');
$savvy->addGlobal('page', $page);
$page->maincontentarea =  $savvy->render($context->output);

$page->contactinfo = '
<p>University of Nebraska&ndash;Lincoln<br />
1400 R Street<br />
Lincoln, NE 68588<br />
402-472-7211</p>';

$page->footercontent = $page->footercontent = '© '.date('Y').' University of Nebraska–Lincoln · Lincoln, NE 68588 · 402-472-7211<br />
    The University of Nebraska–Lincoln is an <a href="http://www.unl.edu/equity/">equal opportunity</a> educator and employer.';
echo $page;
