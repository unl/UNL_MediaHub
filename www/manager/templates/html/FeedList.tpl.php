<?php $baseUrl = UNL_MediaHub_Controller::getURL(); ?>

<div class="dcf-bleed mh-your-channels dcf-pt-7 dcf-pb-8">
    <div class="dcf-wrapper">
        <h2>Your Channels</h2>
        <div class="dcf-d-grid dcf-grid-cols-1 dcf-grid-cols-2@sm dcf-grid-cols-3@md dcf-grid-cols-4@lg dcf-col-gap-vw dcf-row-gap-7">
            <?php foreach ($context->items as $index=>$feed): ?>
                <?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES); ?>
                <div>
                    <div class="dcf-card-as-link">
                        <div class="mh-video-thumb mh-channel-thumb mh-featured-channel">
                            <div class="mh-thumbnail-clip">
                                <?php if($feed->hasImage()): ?>
                                    <img
                                        class="dcf-16x9 dcf-obj-fit-cover"
                                        src="<?php echo $feed_url; ?>/image"
                                        aria-hidden="true"
                                        alt="">
                                <?php else: ?>
                                    <div class="dcf-16x9 dcf-d-flex dcf-ai-center dcf-jc-center">
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
                            <a class="dcf-card-link dcf-txt-decor-hover" href="<?php echo $feed_url ?>"><?php echo UNL_MediaHub::escape($feed->title); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div>
                <a class="dcf-txt-decor-none mh-upload-box" href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <span class="dcf-16x9 dcf-d-flex dcf-ai-center dcf-jc-center">Add New Channel</span>
                </a>
            </div>
        </div>
    </div>
</div>
