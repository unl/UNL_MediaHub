<?php
$html = $savvy->render($context, 'Exception.tpl.php');
?>
<script type="text/javascript">
parent.WDN.jQuery('#dcf-main').prepend(<?php echo json_encode($html); ?>);
parent.WDN.jQuery('.meter').hide();
parent.WDN.jQuery('#fileUpload').show();
</script>