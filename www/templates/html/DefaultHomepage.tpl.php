<?php 
/**
 * @var $context UNL_MediaHub_DefaultHomepage
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<div class="dcf-bleed">
    <div class="mh-search-container">
        <div class="mh-search-image">
            <div id="video-container">
                <video id="video-player" preload="metadata" autoplay="autoplay"  loop="" muted class="fillWidth">
                    <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.mp4" type="video/mp4">
                    <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.webm" type="video/webm">
                    <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.ogv" type="video/ogg">
                    Your browser does not support the video tag. I suggest you upgrade your browser.
                </video>
            </div>
        </div>
        <div class="mh-search">
            <div id="wdn_app_search">
                <div class="dcf-wrapper">
                    <form method="get" action="<?php echo $baseUrl ?>search/">
                        <label for="q_app">Search MediaHub</label>
                        <div class="dcf-input-group">
                            <input id="q_app" name="q" type="text" required />
                            <span class="wdn-input-group-btn">
                                <input type="submit" class="search_submit_button" value="Go" />
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dcf-bleed unl-bg-lightest-gray dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h2 class="dcf-txt-center">
            <span class="dcf-subhead">Popular Videos</span>
        </h2>
        <div class="dcf-grid-thirds@sm">
            <?php foreach ($context->getTopMedia() as $media): ?>
                <div>
                    <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <div class="dcf-grid-thirds@sm dcf-col-gap-6 dcf-txt-center">
            <div class="mh-featured">
                <a class="dcf-txt-decor-none" href="<?php echo $baseUrl ?>search/">
                    <div class="mh-featured-icon centered mh-green">
                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/play-icon.svg">
                            <img src="<?php echo $baseUrl; ?>/templates/html/css/images/play-icon-white.png" alt="browse media">
                        </object>
                    </div>
                    <h2>Browse Media</h2>
                </a>
                <p>
                    Browse MediaHub and find what’s happening at the University of Nebraska–Lincoln. You’ll find documentaries, symphonies, and everything in between. 
                </p>
            </div>
            <div class="mh-featured">
                <a class="dcf-txt-decor-none" href="<?php echo $baseUrl ?>channels/">
                    <div class="mh-featured-icon centered mh-blue">
                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                            <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="explore channels">
                        </object>
                    </div>
                    <h2>Explore Channels</h2>
                </a>
                <p>
                    Channels contain grouped pieces of media. On MediaHub you’ll find channels for podcast, drafting classes, and everything in between. Be sure to check out all the great channels that have been collecting videos. 
                </p>
            </div>
            <div class="mh-featured">
                <a class="dcf-txt-decor-none" href="<?php echo $baseUrl ?>manager/">
                    <div class="mh-featured-icon centered mh-red">
                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/gear-icon.svg">
                            <img src="<?php echo $baseUrl; ?>/templates/html/css/images/gear-icon-white.png" alt="manage media">
                        </object>
                    </div>
                    <h2>Manage Media</h2>
                </a>
                <p>
                    MediaHub is a reliable host for all your audio and video needs. Look professional with the University of Nebraska brand and go places YouTube can’t (like China and K-12 schools). 
                </p>
            </div>
        </div>
    </div>
</div>