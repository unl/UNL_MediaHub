<?php
use UNL\Templates\Templates;

$page = Templates::factory('App', Templates::VERSION_5);

$wdn_include_path = __DIR__ . '/../../..';
if (file_exists($wdn_include_path . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($wdn_include_path);
}

//titles
$page->doctitle     = '<title>Manager | UNL MediaHub | University of Nebraska-Lincoln</title>';
$page->titlegraphic = 'UNL MediaHub';

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

if (isset($_SESSION['notices'])) {
    foreach ($_SESSION['notices'] as $key=>$notice) {
        $page->maincontentarea .= $savvy->render($notice);
        unset($_SESSION['notices'][$key]);
    }
    $page->addScriptDeclaration("WDN.initializePlugin('notice')");
}

$page->maincontentarea = $savvy->render($context->output);

//Footer
$page->contactinfo = $savvy->render($context, 'localfooter.tpl.php');

echo $page;
