<?php
if ($context->isVideo()) {
    echo $savvy->render($context, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/Audio.tpl.php');
}

$url = '';
if (isset($context->id)) {
    $url = $context->id . '/';
}
?>

<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $url ?>embed"></script>

