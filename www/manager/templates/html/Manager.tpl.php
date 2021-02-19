<?php
use UNL\Templates\Templates;

$page = Templates::factory('AppLocal', Templates::VERSION_5_3);

$wdn_include_path = UNL_MediaHub::getRootDir() . '/www';
if (file_exists($wdn_include_path . '/wdn/templates_5.3')) {
    $page->setLocalIncludePath($wdn_include_path);
}

//titles
$page->doctitle     = '<title>Manager | UNL MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = '<a class="dcf-txt-h5" href="' . UNL_MediaHub_Controller::$url . '">MediaHub</a>';

// Add WDN Deprecated Styles
$page->head .= '<link rel="preload" href="/wdn/templates_5.3/css/deprecated.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"> <noscript><link rel="stylesheet" href="/wdn/templates_5.3/css/deprecated.css"></noscript>';

//header
$page->addStyleSheet(UNL_MediaHub_Controller::getURL().'templates/html/css/all.css?v='.UNL_MediaHub_Controller::getVersion());
$page->addStyleSheet(UNL_MediaHub_Manager::getURL().'templates/css/all_manager.css?v='.UNL_MediaHub_Controller::getVersion());

// no menu items, so hide mobile menu
$page->addStyleDeclaration("#dcf-mobile-toggle-menu {display: none!important}");

$scriptBody = '
    var baseurl = "'.UNL_MediaHub_Manager::getURL().'";
    var front_end_baseurl = "'.UNL_MediaHub_Controller::getURL().'";';
$page->addScriptDeclaration($scriptBody);
$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/manager.js?v='.UNL_MediaHub_Controller::getVersion());

//Navigation
$page->appcontrols = $savvy->render(null, 'Navigation.tpl.php');

//Main content
$savvy->addGlobal('page', $page);

$page->maincontentarea = '';
if (isset($_SESSION['notices'])) {
    foreach ($_SESSION['notices'] as $key=>$notice) {
        $page->maincontentarea .= $savvy->render($notice);
        unset($_SESSION['notices'][$key]);
    }
}

$page->maincontentarea .= $savvy->render($context->output);

//Footer
$page->contactinfo = $savvy->render($context, 'localfooter.tpl.php');

echo $page;
