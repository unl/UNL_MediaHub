<audio class="wdn_player video-js vjs-default-skin" preload="auto" data-url="<?php echo $controller->getURL($context); ?>" data-mediahub-id="<?php echo $context->id ?>" title="<?php echo UNL_MediaHub::escape($context->title); ?>" src="<?php echo $context->getMediaURL()?>">
    <?php foreach ($context->getTextTrackURLs() as $lang=>$track):?>
        <track id="mediahub-track-<?php echo $lang ?>" src="<?php echo htmlentities(UNL_MediaHub_Controller::toAgnosticURL($track)) ?>" kind="captions" srclang="<?php echo $lang ?>" />
    <?php endforeach ?>
</audio>
