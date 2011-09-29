<div class="headline_main" id="headline_main_video" style="display:none;">
    <div id="videoData" class="two_col left">
        <?php
        $thumbnail = 'templates/images/thumbs/placeholder.jpg';
        if (isset($context->media)) {
            $thumbnail = $context->media->getThumbnailURL();
        }
        ?>
        <h1>Tell us about your media</h1>
        <ol>
            <li>Pause the video at the right at the frame which you want as the image representation.</li>
            <li>Click the "Set Image" button to save this as your image representation.</li>
            <li>Continue with the form below.</li>
        </ol>
        <h5 class="sec_header">Your Image</h5>
        <div id="imageOverlay">
            <p>We're updating your image, this may take a few minutes depending on video length. <strong>Now is a good time to make sure the information below is up to snuff!</strong></p>
        </div>
        <img src="<?php echo $thumbnail; ?>" id="thumbnail" alt="Thumbnail preview" />
        <a class="action" id="setImage" href="#">Set Image</a>
    </div>
    <div id="videoDisplay" class="two_col right">
        <?php echo $savvy->render('videoDisplay', 'MediaPlayer.tpl.php'); ?>
    </div>
</div>
<div class="headline_main" id="headline_main_audio" style="display:none;">
    <div id="audioPreview" class="two_col left">
        <h1 style="padding: 15px 0 0 20px;">Preview your Audio</h1>
    </div>
    <div id="audioDisplay" class="two_col right">
        <?php echo $savvy->render('audioDisplay', 'MediaPlayer.tpl.php'); ?>
    </div>
</div>