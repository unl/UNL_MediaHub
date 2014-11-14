<div class="wdn-band mh-your-channels">
    <div class="wdn-inner-wrapper">
        <h2 class="wdn-brand">Your Channels</h2>
        <div class="wdn-grid-set">
            <?php foreach ($context->items as $index=>$feed): ?>
                <?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES); ?>
                <div class="<?php echo ($index==0)?'bp2-wdn-col-one-half':'bp2-wdn-col-one-fourth' ?>">
                    <a href="<?php echo $feed_url ?>">
                        <div class="mh-video-thumb mh-featured-channel wdn-center">
                            <div class="mh-thumbnail-clip">
                                <img
                                    src="<?php echo $feed_url; ?>/image"
                                    alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                            </div>
                            <div class="mh-play-button"></div>
                        </div>
                        <div class="mh-video-label wdn-center">
                            <div class="wdn-brand">
                                <?php echo $feed->title; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="bp2-wdn-col-one-fourth">
                <a href="<?php echo UNL_MediaHub_Manager::getURL() ?>?view=feedmetadata">
                    <div class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">New Channel</span></h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
