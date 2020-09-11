<?php
$prefix = 'Video Player: ';
$divStyle = 'padding-top: 56.25%';
if (!$context->media->isVideo()) {
    $prefix = 'Audio Player: ';
    $divStyle = 'height: 5.62em;';
}
?>
<div style="<?php echo $divStyle ?> overflow: hidden; position:relative;">
    <iframe style="bottom: 0; left: 0; position: absolute; right: 0; top: 0; border: 0; height: 100%; width: 100%;" src="<?php echo $controller->getURL($context->media)?>?format=iframe&autoplay=0" title="<?php echo $prefix ?> <?php echo UNL_MediaHub::escape($context->media->title) ?>" allowfullscreen></iframe>
</div>