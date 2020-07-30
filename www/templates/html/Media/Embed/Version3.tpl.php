<?php
$prefix = 'Video Player: ';
$ratioPercentage = '56.25%';
if (!$context->media->isVideo()) {
    $prefix = 'Audio Player: ';
    $ratioPercentage = '25%';
}
?>
<div style="padding-top: <?php echo $ratioPercentage ?>; overflow: hidden; position:relative;">
    <iframe style="bottom: 0; left: 0; position: absolute; right: 0; top: 0; border: 0; height: 100%; width: 100%;" src="<?php echo $controller->getURL($context->media)?>?format=iframe&autoplay=0" title="<?php echo $prefix ?> <?php echo UNL_MediaHub::escape($context->media->title) ?>" allowfullscreen></iframe>
</div>