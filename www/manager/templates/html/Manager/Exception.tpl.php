<?php
if (headers_sent() === FALSE && $code = $context->exception->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}

switch ($code) {

    case 404:
        $title = 'Page Not Found';
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
<div class="dcf-mt-8 dcf-notice dcf-notice-warning" hidden data-no-close-button>
    <h2><?php echo $title; ?></h2>
    <div><?php echo UNL_MediaHub::escape($context->exception->getMessage()); ?></div>
</div>

<div class="dcf-mb-8">
<?php echo $savvy->render($context->getFeeds()); ?>
</div>
