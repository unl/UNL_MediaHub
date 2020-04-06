<?php
use UNL\Templates\Templates;

$page = Templates::factory('AppLocal', Templates::VERSION_5);

$wdn_include_path = UNL_MediaHub::getRootDir() . '/www';
if (file_exists($wdn_include_path . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($wdn_include_path);
}

$savvy->addGlobal('page', $page);

$baseUrl = UNL_MediaHub_Controller::getURL();

//Titles
$page->doctitle = '<title>MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = '<a class="dcf-txt-h5" href="' . UNL_MediaHub_Controller::$url . '">MediaHub</a>';
if ($title = $context->getReplacementData('pagetitle')) {
    $page->pagetitle = $title;
}

// Add WDN Deprecated Styles
$page->head .= '<link rel="preload" href="/wdn/templates_5.1/css/deprecated.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"> <noscript><link rel="stylesheet" href="/wdn/templates_5.1/css/deprecated.css"></noscript>';

//Header
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::getVersion());

$page->addScriptDeclaration('WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");');
$page->addScript($baseUrl . 'templates/html/scripts/frontend.js?v=' . UNL_MediaHub_Controller::getVersion());
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="UNL MediaHub" href="?format=xml" />';
}

//Navigation
$page->appcontrols = $savvy->render(null, 'Navigation.tpl.php');

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
