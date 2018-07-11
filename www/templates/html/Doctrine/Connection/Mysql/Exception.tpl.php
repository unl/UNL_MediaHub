<?php
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}
?>

<script type="text/javascript">
WDN.initializePlugin('notice');
</script>
<div class="wdn_notice alert">
    <div class="message">
        <h4>Whoops! Sorry, there was a database error.</h4>
        <p>Please try back later.</p>
        <?php if (UNL_MediaHub::$verbose_errors): ?>
            <p><?php echo UNL_MediaHub::escape($context->getMessage()); ?></p>
            <!-- <?php echo $context; ?> -->
        <?php endif; ?>
    </div>
</div>
