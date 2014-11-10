<div id="videoDisplay" class="bp2-wdn-col-five-sevenths">
    <?php echo $savvy->render($context, 'MediaPlayer.tpl.php'); ?>
    <a class="wdn-button wdn-button-brand" id="setImage" href="#">Set Image</a>
</div>


<div id="videoData" class="bp2-wdn-col-two-sevenths">
    <h5 class="clear-top">Set a Thumbnail</h5>   
    <ol>
        <li>Pause the video at the right at the frame which you want as the image representation.</li>
        <li>Click the "Set Image" button to save this as your image representation.</li>
        <li>Continue with the form below.</li>
    </ol>
    
    <div id="imageOverlay">
        <p>We're updating your image, this may take a few minutes depending on video length. <strong>Now is a good time to make sure the information below is up to snuff!</strong></p>
    </div>
    <img src="<?php echo $context->getThumbnailURL(); ?>" id="thumbnail" alt="Thumbnail preview" />
    <div id="poster_picker">
        <!-- <a class="action" id="setImage" href="#">Set Image</a> -->

    </div>
    <div id="poster_picker_disabled">
        <p>
            The poster picker has been disabled.  Enable it by <a id="enable_poster_picker" href="#">removing the custom post image url</a>.
        </p>
    </div>
</div>
