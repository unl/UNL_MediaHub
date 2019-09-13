<?php
use UNL\Templates\Templates;
use Themes\Theme;

$theme = new Theme($savvy, UNL_MediaHub_Controller::$theme, UNL_MediaHub_Controller::$template, UNL_MediaHub_Controller::$templateVersion, UNL_MediaHub_Controller::$customThemeTemplate);

$page = $theme->getPage();
$savvy->addGlobal('theme', $theme);
$savvy->addGlobal('page', $page);
$theme->addGlobal('page', $page);

if (file_exists($theme->getWDNIncludePath() . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($theme->getWDNIncludePath());
}

$baseUrl = UNL_MediaHub_Controller::getURL();

// Theme Based Items
if ($theme->getName() == 'UNL') {
    $page->contactinfo = $theme->renderThemeTemplate(null, 'localfooter.tpl.php');
} else {
    $page->optionalfooter = '<div class="dcf-bleed dcf-wrapper">
    <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3">About MediaHub</h3>
    <p>This application is a product of the <a href="https://dxg.unl.edu/">Digital Experience Group at Nebraska</a>. DXG is a partnership of <a href="https://ucomm.unl.edu/">University Communication</a> and <a href="https://its.unl.edu/">Information Technology Services</a> at the University of Nebraska.</p>
</div>';
}

// Shared Items
$page->doctitle = $theme->renderThemeTemplate(null, 'doctitle.tpl.php');
$page->titlegraphic = $theme->renderThemeTemplate(null, 'titlegraphic.tpl.php');

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

echo $page;
