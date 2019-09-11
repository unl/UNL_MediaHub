<?php
/**
 * @var $context UNL_MediaHub_DefaultHomepage
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<div class="dcf-bleed dcf-wrapper dcf-pt-8 dcf-pb-8">
    <div class="dcf-grid-thirds@sm dcf-col-gap-vw dcf-row-gap-7 dcf-txt-center">
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>search/">
                <div class="mh-featured-icon mh-green dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle">
                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/play-icon.svg">
                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/play-icon-white.png" alt="browse media">
                    </object>
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Browse Media</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">Browse MediaHub and find what’s happening. You’ll find documentaries, symphonies, and everything in between.</p>
        </div>
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>channels/">
                <div class="mh-featured-icon mh-blue dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle">
                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="explore channels">
                    </object>
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Explore Channels</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">Channels contain grouped pieces of media. On MediaHub you’ll find channels for podcast, drafting classes, and everything in between. Be sure to check out all the great channels that have been collecting videos.</p>
        </div>
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>manager/">
                <div class="mh-featured-icon mh-red dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle">
                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/gear-icon.svg">
                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/gear-icon-white.png" alt="manage media">
                    </object>
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Manage Media</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">MediaHub is a reliable host for all your audio and video needs. Look professional with our brand and go places YouTube can’t (like China and K-12 schools).</p>
        </div>
    </div>
</div>