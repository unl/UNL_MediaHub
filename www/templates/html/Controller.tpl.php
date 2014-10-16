<?php
require_once 'UNL/Templates.php';

UNL_Templates::$options['version'] = 4.0;

$template = 'Fixed';
$baseUrl = UNL_MediaHub_Controller::getURL();

$page = UNL_Templates::factory($template);

if (isset($GLOBALS['UNLTEMPLATEDEPENDENTSPATH'])) {
    UNL_Templates::$options['templatedependentspath'] = $GLOBALS['UNLTEMPLATEDEPENDENTSPATH'];
}
$page->doctitle     = '<title>MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = 'UNL MediaHub';
$page->pagetitle    = '';
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css');
$page->breadcrumbs = '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li>MediaHub</li></ul>';
$page->head .= '<script>WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");</script>';
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/mediatools.js');
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="UNL MediaHub" href="?format=xml" />';
}

$page->maincontentarea = $savvy->render($context->output);

$page->navlinks = $savvy->render(null, 'Navigation.tpl.php');

$page->leftcollinks = '
<h3>Related Links</h3>
<ul>
    <li><a href="'.UNL_MediaHub_Controller::getURL().'developers" title="Documentation for developers that want to use MediaHub">Developer Documentation</a></li>
    <li><a href="http://itunes.unl.edu/">UNL On iTunes U</a></li>
</ul>
';
$page->contactinfo = '
<p>University of Nebraska&ndash;Lincoln<br />
1400 R Street<br />
Lincoln, NE 68588<br />
402-472-7211</p>';

$page->footercontent = '© '.date('Y').' University of Nebraska–Lincoln · Lincoln, NE 68588 · 402-472-7211<br />
    The University of Nebraska–Lincoln is an <a href="http://www.unl.edu/equity/">equal opportunity</a> educator and employer.
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push([\'_setAccount\', \'UA-22295578-1\']);
    _gaq.push([\'_setDomainName\', \'.unl.edu\']);
    _gaq.push([\'_setAllowLinker\', true]);
    _gaq.push([\'_trackPageview\']);
</script>';

if ($title = $context->getReplacementData('pagetitle')) {
    $page->pagetitle = $title;
}
echo $page;
