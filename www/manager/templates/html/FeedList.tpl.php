<?php $baseUrl = UNL_MediaHub_Controller::getURL(); ?>

<div class="dcf-bleed mh-your-channels dcf-pt-7 dcf-pb-8">
    <div class="dcf-wrapper">
        <h2>Your Channels</h2>
        <div class="dcf-grid-halves@sm dcf-grid-thirds@md dcf-grid-fourths@lg dcf-col-gap-vw dcf-row-gap-7">
            <?php foreach ($context->items as $index=>$feed): ?>
                <?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES); ?>
                <div>
                    <a href="<?php echo $feed_url ?>">
                        <div class="mh-video-thumb mh-channel-thumb mh-featured-channel dcf-txt-center">
                            <div class="dcf-ratio dcf-ratio-16x9 mh-thumbnail-clip">
                                <?php if($feed->hasImage()): ?>
                                    <img
                                    class="dcf-ratio-child dcf-obj-fit-cover"
                                    src="<?php echo $feed_url; ?>/image"
                                    alt="<?php echo UNL_MediaHub::escape($feed->title); ?> Image">
                                <?php else: ?>
                                    <div class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">
                                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                                            <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo UNL_MediaHub::escape($feed->title); ?> Image">
                                        </object>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mh-video-label dcf-txt-center">
                            <p>
                                <?php echo UNL_MediaHub::escape($feed->title); ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <div>
                <a class="dcf-txt-decor-none mh-upload-box dcf-txt-center" href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <span class="dcf-subhead unl-darker-gray">+ New Channel</span>
                </a>
            </div>
        </div>
    </div>
</div>
