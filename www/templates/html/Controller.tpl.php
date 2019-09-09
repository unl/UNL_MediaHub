<?php
use UNL\Templates\Templates;
use Themes\Theme;

$theme = new Theme($savvy, UNL_MediaHub_Controller::$theme, UNL_MediaHub_Controller::$template, UNL_MediaHub_Controller::$templateVersion);

$page = $theme->getPage();
$savvy->addGlobal('page', $page);
$theme->addGlobal('page', $page);

if (file_exists($theme->getWDNIncludePath() . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($theme->getWDNIncludePath());
}

$baseUrl = UNL_MediaHub_Controller::getURL();

if ($theme->getName() == 'UNL') {
    //Titles
    $page->doctitle = '<title>MediaHub | University of Nebraska-Lincoln</title>';
}

$page->titlegraphic = '<a class="dcf-txt-h5" href="' . UNL_MediaHub_Controller::$url . '">MediaHub</a>';

//Header
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::getVersion());

$page->addScript(UNL_MediaHub_Controller::getURL().'templates/html/scripts/frontend.js?v=' . UNL_MediaHub_Controller::getVersion());
if (!$context->output instanceof UNL_MediaHub_FeedAndMedia) {
    $page->head .= '<link rel="alternate" type="application/rss+xml" title="MediaHub" href="?format=xml" />';
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
$page->contactinfo = $theme->renderThemeTemplate(null, 'localfooter.tpl.php');

echo $page;
