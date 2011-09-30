<script type="text/javascript">
<?php
if (isset($context->options['url'])
    && filter_var($context->options['url'], FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
    echo "parent.upload.stop('{$context->options['url']}');";
} else {
    echo "parent.upload.stop(false);";
}
?>
</script>
Upload complete! Your media is at <?php echo urlencode($context->options['url']); ?>