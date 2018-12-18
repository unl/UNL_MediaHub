<?php
use UNL\Templates\Templates;

$page = Templates::factory('Fixed', Templates::VERSION_5);

$wdn_include_path = UNL_MediaHub::getRootDir() . '/www';
if (file_exists($wdn_include_path . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($wdn_include_path);
}

$savvy->addGlobal('page', $page);

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
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::getVersion());
$page->addScriptDeclaration('WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");');
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/frontend.js?v=' . UNL_MediaHub_Controller::getVersion());
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="UNL MediaHub" href="?format=xml" />';
}

//Navigation
$page->breadcrumbs = '<ol> <li><a href="http://www.unl.edu/">Nebraska</a></li> <li><a href="' . $baseUrl . '">MediaHub</a></li></ol>';
$page->navlinks = $savvy->render(null, 'Navigation.tpl.php');

//Main content
if (isset($_SESSION['notices'])) {
    foreach ($_SESSION['notices'] as $key=>$notice) {
        $page->maincontentarea .= $savvy->render($notice);
        unset($_SESSION['notices'][$key]);
    }
    $page->addScriptDeclaration("WDN.initializePlugin('notice')");
}

$page->maincontentarea = $savvy->render($context->output);

//Footer
$page->contactinfo = $savvy->render(null, 'localfooter.tpl.php');

echo $page;
