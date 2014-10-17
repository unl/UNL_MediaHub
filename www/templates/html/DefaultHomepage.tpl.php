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
           
                    <form method="get" action='<?php echo UNL_MediaHub_Controller::getURL() ?>
                        ' search/>
                        <div class="wdn-input-group bp3-wdn-col-one-third centered">
                            <label for="q_app">Search MediaHub</label>
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

<div class="wdn-band">
    <div class="wdn-inner-wrapper">
        <div class="bp2-wdn-grid-set-thirds wdn-center">
            <div class="wdn-col mh-featured">
                <div class="mh-featured-icon centered mh-red">
                    <a href="#" title="MANAGE MEDIA">
                        <div class="wdn-icon-wrench"></div>
                    </a>
                </div>
                <h2 class="wdn-brand">
                    <a href="#">MANAGE MEDIA</a>
                </h2>
                <p>
                    Channels are groups of related media. Any media can be part of any channel. Create your own.
                </p>
            </div>
            <div class="wdn-col mh-featured">
                <div class="mh-featured-icon centered mh-green">
                    <a href="#" title="BROWSE VIDEOS">
                        <div class="wdn-icon-search"></div>
                    </a>
                </div>
                <h2 class="wdn-brand">
                    <a href="#">BROWSE VIDEOS</a>
                </h2>
                <p>
                    Channels are groups of related media. Any media can be part of any channel. Create your own.
                </p>
            </div>
            <div class="wdn-col mh-featured">
                <div class="mh-featured-icon centered mh-blue">
                    <a href="#" title="EXPLORE CHANNELS">
                        <div class="wdn-icon-info"></div>
                    </a>
                </div>
                <h2 class="wdn-brand">
                    <a href="#">EXPLORE CHANNELS</a>
                </h2>
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
        <div class="bp1-wdn-grid-set-thirds">
            <?php
            foreach ($context->latest_media->items as $media):
                ?>
                <div class="wdn-col">
                    <?php echo $savvy->render($media, 'templates/html/Media/teaser.tpl.php'); ?>
                </div>
                <?php
            endforeach;
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    var vid = document.getElementById("video-player"); // run main video at half speed
    vid.playbackRate = 0.25;


</script>