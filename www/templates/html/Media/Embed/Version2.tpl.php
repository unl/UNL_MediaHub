<?php
$url = '';
if (isset($context->media)) {
    $url = $context->media->id . '/';
}
?>
<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $url ?>embed"></script>
<div id="mediahub_embed_<?php echo $context->media->id ?>">
    <a href="<?php echo $controller->getURL($context->media)?>"><?php echo $context->media->title ?></a>
</div>