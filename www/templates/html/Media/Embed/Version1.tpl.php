<?php
$markup = $savvy->render($context->media, 'MediaPlayer.tpl.php');
echo trim(preg_replace('/(<script[^>]*>.*<\/script>)/is', '', $markup));
?>

<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $context->media->id ?>/embed/1"></script>