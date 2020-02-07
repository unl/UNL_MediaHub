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
                            <div class="mh-thumbnail-clip">
                                <?php if($feed->hasImage()): ?>
                                    <img
                                    src="<?php echo $feed_url; ?>/image"
                                    alt="<?php echo UNL_MediaHub::escape($feed->title); ?> Image">
                                <?php else: ?>
                                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo UNL_MediaHub::escape($feed->title); ?> Image">
                                    </object>
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
                <a class="dcf-txt-decor-none" href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <div class="mh-upload-box dcf-txt-center">
                        <h2>+<span class="dcf-subhead">New Channel</span></h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
