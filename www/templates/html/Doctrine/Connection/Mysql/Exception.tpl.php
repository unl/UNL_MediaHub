<?php
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}
?>

<div class="dcf-mt-8 dcf-notice dcf-notice-warning" hidden data-no-close-button>
    <h2>Whoops! Sorry, there was a database error.</h2>
    <div>
        <p>Please try back later.</p>
        <?php if (UNL_MediaHub::$verbose_errors): ?>
            <p><?php echo UNL_MediaHub::escape($context->getMessage()); ?></p>
            <!-- <?php echo $context; ?> -->
        <?php endif; ?>
    </div>
</div>
