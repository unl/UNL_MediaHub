<?php
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}
$page->addScriptDeclaration("WDN.initializePlugin('notice')");

switch ($code) {
    case 200:
        $title = 'Media Not Ready';
        break;

    case 404:
        $title = 'Media Not Found';
        break;

    case 403:
        $title = 'Access Denied!';
        break;

    default:
        $title = 'Whoops! Sorry, there was an error.';
        break;
}
?>

<div class="dcf-mt-8 dcf-mb-8 wdn_notice alert">
    <div class="message">
        <h1 class="title"><?php echo $title; ?></h1>
        <p><?php echo UNL_MediaHub::escape($context->getMessage()); ?></p>
    </div>
</div>