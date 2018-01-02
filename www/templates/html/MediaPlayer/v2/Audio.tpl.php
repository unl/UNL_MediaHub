<audio class="wdn_player" preload="auto" data-url="<?php echo $controller->getURL($context); ?>" data-mediahub-id="<?php echo $context->id ?>" title="<?php echo $context->title; ?>" src="<?php echo $context->getMediaURL()?>">
    <?php foreach ($context->getTextTrackURLs() as $lang=>$track):?>
        <track src="<?php echo htmlentities(UNL_MediaHub_Controller::toAgnosticURL($track)) ?>" kind="captions" srclang="<?php echo $lang ?>" />
    <?php endforeach ?>
</audio>
