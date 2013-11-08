<div id="videoData" class="two_col left">
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
    <img src="<?php echo $context->getThumbnailURL(); ?>" id="thumbnail" alt="Thumbnail preview" />
    <div id="poster_picker">
        <a class="action" id="setImage" href="#">Set Image</a>
    </div>
    <div id="poster_picker_disabled">
        <p>
            The poster picker has been disabled.  Enable it by <a id="enable_poster_picker" href="#">removing the custom post image url</a>.
        </p>
    </div>
</div>

<div id="videoDisplay" class="two_col right">
    <?php echo $savvy->render($context, 'MediaPlayer.tpl.php'); ?>
</div>