<?php
// Ensure output is not cached
header('Expires: Tue, 08 Oct 1991 00:00:00 GMT');
header('Cache-Control: no-cache, must-revalidate');
?>
<script type="text/javascript">
    <?php echo "parent.upload.updateInfo(".$context->getPercentComplete().")"; ?>
</script>
