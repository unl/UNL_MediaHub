<?php
$url = '';
if (isset($context->media)) {
    $url = $context->media->id . '/';
}
?>
<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $url ?>embed"></script>
<div id="mediahub_embed_<?php echo $context->media->id ?>">
    <noscript>Sorry, we are unable to embed this media because you have javascript disabled.</noscript>
</div>