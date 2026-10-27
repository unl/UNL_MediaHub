<?php
$html = $savvy->render($context, 'Exception.tpl.php');
?>
<script type="text/javascript">
  const main = document.getElementById('dcf-main');
  if (main) {
    main.insertAdjacentHTML('afterbegin', <?php echo json_encode($html); ?>);
  }

  document.querySelectorAll('.meter').forEach(function (meter) {
    meter.style.display = 'none';
  });

  const fileUpload = document.getElementById('fileUpload');
  if (fileUpload) {
    fileUpload.style.display = '';
  }
</script>