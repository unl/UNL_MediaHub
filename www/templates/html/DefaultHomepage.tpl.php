<?php 
/**
 * @var $context UNL_MediaHub_DefaultHomepage
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<div class="wdn-band">
    <div class="mh-search-container">
        <div class="mh-search-image">
            <div id="video-container">
                <video id="video-player" preload="metadata" autoplay="autoplay" loop="" class="fillWidth">
                    <source src="http://admissions.unl.edu/includes/videos/why-unl/why-unl.mp4" type="video/mp4">
                    <source src="http://admissions.unl.edu/includes/videos/why-unl/why-unl.webm" type="video/webm">
                    <source src="http://admissions.unl.edu/includes/videos/why-unl/why-unl.ogg" type="video/ogg">
                    Your browser does not support the video tag. I suggest you upgrade your browser.
                </video>
            </div>
        </div>
        <div class="mh-search">
            <div id="wdn_app_search">
                <div class="wdn-inner-wrapper wdn-inner-padding-sm ">
                    <form method="get" action="<?php echo $baseUrl ?>search/">
                        <label for="q_app">Search MediaHub</label>
                        <div class="wdn-input-group">
                            <input id="q_app" name="q" type="text" required />
                            <span class="wdn-input-group-btn ">
                                <input type="submit" class="search_submit_button" value="Go" />
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wdn-band">
    <div class="wdn-inner-wrapper">
        <div class="bp2-wdn-grid-set-thirds wdn-center">
            <div class="wdn-col mh-featured">
                <a href="<?php echo $baseUrl ?>manager/">
                    <div class="mh-featured-icon centered mh-red">
                        <div class="wdn-icon"><img src="<?php echo $baseUrl ?>templates/html/css/images/gear-icon-white.png"></div>
                    </div>
                    <h2 class="wdn-brand">Manage Media</h2>
                </a>
                <p>
                    Channels are groups of related media. Any media can be part of any channel. Create your own.
                </p>
            </div>
            <div class="wdn-col mh-featured">
                <a href="<?php echo $baseUrl ?>search/">
                    <div class="mh-featured-icon centered mh-red">
                        <div class="wdn-icon"><img src="<?php echo $baseUrl ?>templates/html/css/images/play-icon-white.png"></div>
                    </div>
                    <h2 class="wdn-brand">Browse Media</h2>
                </a>
                <p>
                    Channels are groups of related media. Any media can be part of any channel. Create your own.
                </p>
            </div>
            <div class="wdn-col mh-featured">
                <a href="<?php echo $baseUrl ?>channels/">
                    <div class="mh-featured-icon centered mh-red">
                        <div class="wdn-icon"><img src="<?php echo $baseUrl ?>templates/html/css/images/channel-icon-white.png"></div>
                    </div>
                    <h2 class="wdn-brand">Explore Channels</h2>
                </a>
                <p>
                    Channels are groups of related media. Any media can be part of any channel. Create your own.
                </p>
            </div>

        </div>
    </div>
</div>

<div class="wdn-band wdn-light-neutral-band">
    <div class="wdn-inner-wrapper">
        <h2 class="wdn-brand wdn-center">
            <span class="wdn-subhead">Latest Video</span>
        </h2>
        <div class="bp2-wdn-grid-set-thirds">
            <?php foreach ($context->latest_media->items as $media): ?>
                <div class="wdn-col">
                    <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    var vid = document.getElementById("video-player"); // run main video at half speed
    vid.playbackRate = 0.25;


</script>