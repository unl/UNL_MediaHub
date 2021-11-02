<?php
/* @var $context Exception */
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code.' '.$context->getMessage());
    header('Status: '.$code.' '.$context->getMessage());
}
?>

<div class="dcf-mt-8 dcf-mb-8 dcf-notice dcf-notice-warning" hidden data-no-close-button>
   <h2>Whoops! Sorry, there was an error:</h2>
   <div><?php echo $context->getMessage(); ?></div>
</div>
