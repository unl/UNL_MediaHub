<?php
if (false == headers_sent() && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}

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

<script>
    window.addEventListener('inlineJSReady', function(e) {
        require(['wdn'], function(wdn) {
            wdn.initializePlugin('notice');
        });
    });
</script>
<div class="dcf-mt-8 dcf-mb-8 wdn_notice alert">
    <div class="message" style="color: #fff; padding: 6px;">
        <h1 class="title"><?php echo $title; ?></h1>
        <p><?php echo UNL_MediaHub::escape($context->getMessage()); ?></p>
    </div>
</div>