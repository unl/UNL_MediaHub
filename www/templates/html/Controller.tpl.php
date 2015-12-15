<?php
use UNL\Templates\Templates;

$page = Templates::factory('Fixed', Templates::VERSION_4_1);

$baseUrl = UNL_MediaHub_Controller::getURL();

$page->doctitle     = '<title>MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = 'UNL MediaHub';
$page->pagetitle    = '';
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::VERSION);
$page->breadcrumbs = '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="' . $baseUrl . '">MediaHub</a></li></ul>';
$page->head .= '<script>WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");</script>';
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/mediatools.js?v=' . UNL_MediaHub_Controller::VERSION);
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="UNL MediaHub" href="?format=xml" />';
}

$page->maincontentarea = '';
if (isset($_SESSION['notices'])) {
    foreach ($_SESSION['notices'] as $key=>$notice) {
        $page->maincontentarea .= $savvy->render($notice);
        unset($_SESSION['notices'][$key]);
    }
    $page->maincontentarea .= '<script>WDN.initializePlugin("notice");</script>';
}

$page->maincontentarea .= $savvy->render($context->output);

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

$page->contactinfo .= '
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
