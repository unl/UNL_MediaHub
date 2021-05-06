<?php $baseUrl = UNL_MediaHub_Controller::getURL();
if (count($context->items)) :
?>
<h5><?php echo UNL_MediaHub::escape($context->label); ?></h5>
<div class="channels">
    <?php foreach ($context->items as $channel): ?>
    <?php $feed_url = UNL_MediaHub_Controller::getURL($channel); ?>
    <a href="<?php echo $feed_url ?>" title="<?php echo UNL_MediaHub::escape($channel->description); ?>">
        <div class="mh-channel-thumb mh-featured-channel">
            <div class="dcf-ratio dcf-ratio-16x9 mh-thumbnail-clip">
                <?php if($channel->hasImage()): ?>
                    <img
                        class="dcf-ratio-child dcf-obj-fit-cover"
                        src="<?php echo $feed_url; ?>/image"
                        aria-hidden="true"
                        alt="">
                <?php else: ?>
                    <div class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">
                        <img
                            class="dcf-h-8 dcf-w-8"
                            src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg"
                            height="51"
                            width="51"
                            aria-hidden="true"
                            alt="">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mh-video-label dcf-txt-center">
            <span class="title"><?php echo UNL_MediaHub::escape($channel->title); ?></span>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>