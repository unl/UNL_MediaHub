<?php
use UNL\Templates\Templates;
use DCF\Theme;

//$page = Theme\DCF_Theme::factory('App', Templates::VERSION_5);
$page = Theme\DCF_Theme::factory('Custom', 1);

$wdn_include_path = $_SERVER['DOCUMENT_ROOT']; //UNL_MediaHub::getRootDir() . '/www';
if (file_exists($wdn_include_path . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($wdn_include_path);
}

$savvy->addGlobal('page', $page);

$baseUrl = UNL_MediaHub_Controller::getURL();

//Titles
$page->doctitle = '<title>MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = '<a class="dcf-txt-h5" href="' . UNL_MediaHub_Controller::$url . '">MediaHub</a>';

//Header
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::getVersion());

$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/frontend.js?v=' . UNL_MediaHub_Controller::getVersion());
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
