<?php
$html = $savvy->render($context, 'Exception.tpl.php');
?>
<script type="text/javascript">
  require(['jquery'], function($) {
    $('#dcf-main').prepend(<?php echo json_encode($html); ?>);
    $('.meter').hide();
    $('#fileUpload').show();
  });
</script>