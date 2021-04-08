<audio class="mh_player video-js vjs-default-skin" preload="auto" data-url="<?php echo $controller->getURL($context); ?>" data-mediahub-id="<?php echo (int)$context->id ?>" poster="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getThumbnailURL()); ?>" src="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getMediaURL()); ?>" title="<?php echo UNL_MediaHub::escape($context->title); ?>">
    <?php foreach ($context->getTextTrackURLs() as $lang=>$track):?>
        <track id="mediahub-track-<?php echo UNL_MediaHub::escape($lang) ?>" src="<?php echo UNL_MediaHub::escape(UNL_MediaHub_Controller::toAgnosticURL($track)) ?>" kind="captions" srclang="<?php echo UNL_MediaHub::escape($lang) ?>" />
    <?php endforeach ?>
</audio>
