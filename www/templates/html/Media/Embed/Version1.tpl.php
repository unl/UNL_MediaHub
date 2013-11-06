<?php
if ($context->media->isVideo()) {
    echo $savvy->render($context->media, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context->media, 'MediaPlayer/Audio.tpl.php');
}
?>

<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $context->media->id ?>/embed/1"></script>
