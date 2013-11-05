<?php
$markup = $savvy->render($context->media, 'MediaPlayer.tpl.php');

//Add slashes so that it is javascirpt safe
$markup = addslashes($markup);

//Replace newlines with spaces
$markup = trim(preg_replace('/\s+/', ' ', $markup));
?>
var markup = "<?php echo $markup?>";

WDN.loadJQuery(function() {
    WDN.jQuery('#mediahub_embed_<?php echo $context->media->id?>').html(markup);
});
