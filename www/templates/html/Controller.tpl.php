<?php
use UNL\Templates\Templates;
use Themes\Theme;

$theme = new \UNL\Templates\Theme($savvy, UNL_MediaHub_Controller::$themePath, UNL_MediaHub_Controller::$template, UNL_MediaHub_Controller::$templateVersion, UNL_MediaHub_Controller::$customThemeTemplate);

$page = $theme->getPage();
$savvy->addGlobal('theme', $theme);
$savvy->addGlobal('page', $page);
$theme->addGlobal('page', $page);

$baseUrl = UNL_MediaHub_Controller::getURL();

// Theme Based Items
if (!$theme->isCustomTheme()) {
    // UNL Theme
    $theme->setWDNIncludePath(__DIR__ . '/../..');
    if (file_exists($theme->getWDNIncludePath() . '/wdn/templates_5.3')) {
        $page->setLocalIncludePath($theme->getWDNIncludePath());
    }

    $page->contactinfo = $theme->renderThemeTemplate(null, 'localfooter.tpl.php');

    $page->addScriptDeclaration('WDN.setPluginParam("idm", "logout", "' . $baseUrl . '?logout");');

    $page->addScriptDeclaration("WDN.initializePlugin('card-as-link');");

} else {
    // Custom Theme
    $page->optionalfooter = '<div class="dcf-bleed dcf-wrapper">
    <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3">About MediaHub</h3>
    <p>
        This application is a product of the <a href="https://dxg.unl.edu/">Digital
        Experience Group</a> at <a href="https://www.unl.edu/">Nebraska</a>. DXG
        is a part of <a href="https://ucomm.unl.edu/">University Communication &amp; Marketing</a>.
    </p>
</div>';
    $page->addScriptDeclaration("
    require(['dcf/dcf-cardAsLink'], function(DCFCardAsLinkModule) {
        // Card as Links
        var cards = document.querySelectorAll('.dcf-card-as-link');
        var cardAsLink = new DCFCardAsLinkModule.DCFCardAsLink(cards);
        cardAsLink.initialize();
    });");
}

// Shared Items
$page->doctitle = $theme->renderThemeTemplate(null, 'doctitle.tpl.php');
$page->titlegraphic = $theme->renderThemeTemplate(null, 'titlegraphic.tpl.php');

//Header
$page->addStyleSheet($baseUrl . 'templates/html/css/all.css?v=' . UNL_MediaHub_Controller::getVersion());

$page->addScript($baseUrl . 'templates/html/scripts/frontend.js?v=' . UNL_MediaHub_Controller::getVersion());

if (is_array($context->output) && $context->output[0] instanceof UNL_MediaHub_FeedAndMedia) {
    $alternateLinkTitle = trim(htmlentities($context->output[0]->feed->title, ENT_QUOTES));
} else {
    $alternateLinkTitle = UNL_MediaHub_Controller::$appName;
}

$xmlHref = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?format=xml";
$page->head .= '<link rel="alternate" type="application/rss+xml" title="'. $alternateLinkTitle .'" href="' . $xmlHref .'" />';

$siteNotice = isset($siteNotice) ? $siteNotice : NULL;
UNL_MediaHub_Controller::sharedTemplatePageActions($siteNotice, $context, $page, $savvy);

echo $page;
