<?php $baseUrl = UNL_MediaHub_Controller::getURL(); ?>

<div class="wdn-band mh-your-channels">
    <div class="wdn-inner-wrapper">
        <h2 class="wdn-brand">Your Channels</h2>
        <div class="bp2-wdn-grid-set-fourths wdn-grid-clear">
            <?php foreach ($context->items as $index=>$feed): ?>
                <?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES); ?>
                <div class="wdn-col">
                    <a href="<?php echo $feed_url ?>">
                        <div class="mh-video-thumb mh-channel-thumb mh-featured-channel wdn-center">
                            <div class="mh-thumbnail-clip">
                                <?php if($feed->hasImage()): ?>
                                    <img
                                    src="<?php echo $feed_url; ?>/image"
                                    alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                                <?php else: ?>
                                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                                    </object>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mh-video-label wdn-center">
                            <p>
                                <?php echo $feed->title; ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="wdn-col">
                <a href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <div class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">New Channel</span></h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>