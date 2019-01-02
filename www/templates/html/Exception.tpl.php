<?php
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}
$page->addScriptDeclaration("WDN.initializePlugin('notice')")
?>

<div class="wdn_notice alert">
    <div class="message">
        <h1 class="title">Whoops! Sorry, there was an error.</h1>
        <p><?php echo UNL_MediaHub::escape($context->getMessage()); ?></p>
    </div>
</div>