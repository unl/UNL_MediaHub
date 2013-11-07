<div id="mediahub_embed_<?php echo $context->media->id ?>">
    <a href="<?php echo $controller->getURL($context->media)?>"><?php echo $context->media->title ?></a>
</div>
<script type="text/javascript" src="<?php echo UNL_MediaHub_Controller::$url?>media/<?php echo $context->media->id  ?>/embed"></script>
