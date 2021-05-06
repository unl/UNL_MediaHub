<?php $baseUrl = UNL_MediaHub_Controller::getURL(); ?>

<div class="dcf-bleed mh-your-channels dcf-pt-7 dcf-pb-8">
    <div class="dcf-wrapper">
        <h2>Your Channels</h2>
        <div class="dcf-grid-halves@sm dcf-grid-thirds@md dcf-grid-fourths@lg dcf-col-gap-vw dcf-row-gap-7">
            <?php foreach ($context->items as $index=>$feed): ?>
                <?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES); ?>
                <div>
                    <a href="<?php echo $feed_url ?>">
                        <div class="mh-video-thumb mh-channel-thumb mh-featured-channel">
                            <div class="dcf-ratio dcf-ratio-16x9 mh-thumbnail-clip">
                                <?php if($feed->hasImage()): ?>
                                    <img
                                        class="dcf-ratio-child dcf-obj-fit-cover"
                                        src="<?php echo $feed_url; ?>/image"
                                        aria-hidden="true"
                                        alt="">
                                <?php else: ?>
                                    <div class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">
                                        <img
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
                            <p>
                                <?php echo UNL_MediaHub::escape($feed->title); ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <div>
                <a class="dcf-ratio dcf-ratio-16x9 dcf-txt-decor-none mh-upload-box" href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <span class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">Add New Channel</span>
                </a>
            </div>
        </div>
    </div>
</div>
