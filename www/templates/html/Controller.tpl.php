<?php
use UNL\Templates\Templates;

$page = Templates::factory('Fixed', Templates::VERSION_4_1);

$wdn_include_path = __DIR__ . '/../..';
if (file_exists($wdn_include_path . '/wdn/templates_4.1')) {
    $page->setLocalIncludePath($wdn_include_path);
}

$baseUrl = UNL_MediaHub_Controller::getURL();

//Titles
$page->doctitle = '<title>MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = 'UNL MediaHub';
$page->pagetitle = '';
$page->affiliation = '';
if ($title = $context->getReplacementData('pagetitle')) {
    $page->pagetitle = $title;
}

//Header
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::VERSION);
$page->head .= '<script>WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");</script>';
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/mediatools.js?v=' . UNL_MediaHub_Controller::VERSION);
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="UNL MediaHub" href="?format=xml" />';
}

//Navigation
$page->breadcrumbs = '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="' . $baseUrl . '">MediaHub</a></li></ul>';
$page->navlinks = $savvy->render(null, 'Navigation.tpl.php');

//Main content
$page->maincontentarea = '';
if (isset($_SESSION['notices'])) {
    foreach ($_SESSION['notices'] as $key=>$notice) {
        $page->maincontentarea .= $savvy->render($notice);
        unset($_SESSION['notices'][$key]);
    }
    $page->maincontentarea .= '<script>WDN.initializePlugin("notice");</script>';
}

$page->maincontentarea .= $savvy->render($context->output);

//Footer
$page->leftcollinks = $savvy->render(null, 'localfooter.tpl.php');

echo $page;
