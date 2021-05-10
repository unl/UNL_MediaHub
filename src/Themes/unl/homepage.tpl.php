<?php
$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<div class="dcf-bleed dcf-overflow-hidden dcf-bg-cover mh-search-container">
    <div class="mh-search-bg-video">
        <video class="dcf-d-block dcf-w-100%" id="video-player" preload="metadata" autoplay="autoplay"  loop="" muted>
            <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.mp4" type="video/mp4">
            <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.webm" type="video/webm">
            <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.ogv" type="video/ogg">
            Your browser does not support the video tag. I suggest you upgrade your browser.
        </video>
    </div>
    <div class="dcf-absolute dcf-h-100% dcf-w-100% dcf-pin-top dcf-pin-left dcf-d-flex dcf-ai-center dcf-jc-center dcf-wrapper mh-search">
        <form class="dcf-w-max-lg dcf-form dcf-form-controls-inline" method="get" action="<?php echo $baseUrl ?>search/">
            <label class="dcf-inverse" for="q_app">Search MediaHub</label>
            <div class="dcf-input-group">
                <input class="dcf-bg-transparent dcf-inverse" id="q_app" name="q" type="text" required />
                <input class="dcf-btn dcf-btn-primary" type="submit" value="Go" />
            </div>
        </form>
    </div>
</div>
<?php
    $topMedia = $context->getTopMedia();
    if (count($topMedia) > 0):
?>
    <div class="dcf-bleed dcf-wrapper dcf-pt-7 dcf-pb-8 unl-bg-lightest-gray">
        <h2 class="dcf-txt-center dcf-subhead dcf-mb-6">Popular Videos</h2>
        <div class="dcf-grid-halves@sm dcf-grid-thirds@md dcf-col-gap-vw dcf-row-gap-7">
        <?php foreach ($context->getTopMedia() as $media): ?>
            <div>
                <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<div class="dcf-bleed dcf-wrapper dcf-pt-8 dcf-pb-8">
    <div class="dcf-grid-thirds@sm dcf-col-gap-vw dcf-row-gap-7 dcf-txt-center">
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>search/">
                <div class="mh-featured-icon mh-green dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle">
                    <img src="<?php echo $baseUrl; ?>/templates/html/css/images/play-icon.svg" aria-hidden="true" alt="">
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Browse Media</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">Browse MediaHub and find what’s happening at the University of Nebraska–Lincoln. You’ll find documentaries, symphonies, and everything in between.</p>
        </div>
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>channels/">
                <div class="mh-featured-icon mh-blue dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle">
                    <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg" aria-hidden="true" alt="">
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Explore Channels</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">Channels contain grouped pieces of media. On MediaHub you’ll find channels for podcast, drafting classes, and everything in between. Be sure to check out all the great channels that have been collecting videos.</p>
        </div>
        <div class="mh-featured">
            <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-txt-decor-hover" href="<?php echo $baseUrl ?>manager/">
                <div class="mh-featured-icon dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-10 dcf-w-10 dcf-circle unl-bg-scarlet">
                    <img src="<?php echo $baseUrl; ?>/templates/html/css/images/gear-icon.svg" aria-hidden="true" alt="">
                </div>
                <h2 class="dcf-mt-2 dcf-txt-h4">Manage Media</h2>
            </a>
            <p class="dcf-txt-sm dcf-mb-0 unl-font-sans">MediaHub is a reliable host for all your audio and video needs. Look professional with the University of Nebraska brand and go places YouTube can’t (like China and K-12 schools).</p>
        </div>
    </div>
</div>