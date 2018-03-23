<div id="mediahub_embed_<?php echo $context->media->id ?>" class="mediahub-embed" data-mediahub-embed-version="2">
    <a href="<?php echo $controller->getURL($context->media)?>"><?php echo UNL_MediaHub::escape($context->media->title) ?></a>
</div>
<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::toAgnosticURL(UNL_MediaHub_Controller::$url)?>media/<?php echo $context->media->id  ?>/embed/2?autoplay=0"></script>
