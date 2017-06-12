<div class="wdn-responsive-embed wdn-aspect16x9">
    <?php
    $prefix = 'Video Player: ';
    if (!$context->media->isVideo()) {
        $prefix = 'Audio Player: ';
    }
    ?>
    <iframe src="<?php echo $controller->getURL($context->media)?>?format=iframe&autoplay=0" title="<?php echo $prefix ?> <?php echo htmlentities($context->media->title) ?>" allowfullscreen></iframe>
</div>