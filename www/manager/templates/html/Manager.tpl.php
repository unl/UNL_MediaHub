<?php
use UNL\Templates\Templates;
use Themes\Theme;

$theme = new \UNL\Templates\Theme($savvy, UNL_MediaHub_Controller::$themePath, UNL_MediaHub_Controller::$template, UNL_MediaHub_Controller::$templateVersion, UNL_MediaHub_Controller::$customThemeTemplate);

$page = $theme->getPage();
$savvy->addGlobal('theme', $theme);
$savvy->addGlobal('page', $page);
$theme->addGlobal('page', $page);

// Theme Based Items
if (!$theme->isCustomTheme()) {
    // UNL Theme
    $theme->setWDNIncludePath(__DIR__ . '/../../..');
    if (file_exists($theme->getWDNIncludePath() . '/wdn/templates_5.3')) {
        $page->setLocalIncludePath($theme->getWDNIncludePath());
    }

    $page->contactinfo = $theme->renderThemeTemplate(null, 'localfooter.tpl.php');

    $page->addScriptDeclaration('WDN.setPluginParam("idm", "logout", "' . UNL_MediaHub_Controller::getURL() . '?logout");');

    $page->addScriptDeclaration("WDN.initializePlugin('card-as-link');");
} else {
    $page->optionalfooter = '<div class="dcf-bleed dcf-wrapper">
    <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3">About MediaHub</h3>
    <p>
        This application is a product of the <a href="https://dxg.unl.edu/">Digital
        Experience Group</a> at <a href="https://www.unl.edu/">Nebraska</a>. DXG
        is a part of <a href="https://ucomm.unl.edu/">University Communication &amp; Marketing</a>.
    </p>
</div>';

    $page->addScriptDeclaration("
    // Card as Links
    require(['dcf/dcf-cardAsLink'], function(DCFCardAsLinkModule) {
        // Card as Links
        var cards = document.querySelectorAll('.dcf-card-as-link');
        var cardAsLink = new DCFCardAsLinkModule.DCFCardAsLink(cards);
        cardAsLink.initialize();
    });");
}

// Shared Items
$page->doctitle     = $theme->renderThemeTemplate(null, 'doctitle.tpl.php');
$page->titlegraphic = $theme->renderThemeTemplate(null, 'titlegraphic.tpl.php');

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

$siteNotice = isset($siteNotice) ? $siteNotice : NULL;
UNL_MediaHub_Controller::sharedTemplatePageActions($siteNotice, $context, $page, $savvy);

echo $page;
