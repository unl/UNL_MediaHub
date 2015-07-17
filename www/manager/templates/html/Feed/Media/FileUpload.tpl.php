<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js');
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/uploadScript.js');
?>

<div class="wdn-band wdn-light-neutral-band mh-upload-band">
    <div class="wdn-inner-wrapper">
        <h1 class="wdn-brand clear-top">
            Manage Media
            <span class="wdn-subhead wdn-pull-right"><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></span>
        </h1>
        <form action="?" method="post" id="add_media">
            <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
            <input type="hidden" id="media_url" name="url" value="">
            <div class="wdn-grid-set">
                <div id="mh_upload_media_container" class="bp2-wdn-col-three-sevenths">
                    <div id="mh_upload_media" class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">Add Media</span></h2>
                        <p>.mp4 or .mp3<br>(Maximum file size: 900mb)</p>
                    </div>
                    <div id="filelist" class="mh-upload-box wdn-center">
                        Your browser doesn't have Flash, Silverlight or HTML5 support.
                    </div>
                </div>
                <div class="bp2-wdn-col-four-sevenths">
                    <div class="wdn-grid-set">
                        <div class="bp1-wdn-col-three-fifths">
                            <ol>
                                <li class="clear-top">
                                    <label for="title">
                                        Title
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" class="required-entry">
                                </li>
                                <li>
                                    <label for="author">
                                        Author
                                        <span class="required">*</span>
                                    </label>
                                    <div class="mh-tooltip wdn-icon-info italic hang-right" id="author-details">
                                        <div>
                                            Name of media creator
                                        </div>
                                    </div>
                                    <input type="text" id="author" name="author" class="required-entry" aria-describedby="author-details">
                                </li>
                                <li>
                                    <label for="description">
                                        Description
                                        <span class="required">*</span>
                                    </label>
                                    <div class="mh-tooltip wdn-icon-info italic" id="description-details">
                                        <div>
                                            Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.
                                        </div>
                                    </div>
                                    <textarea rows="4" type="text" id="description" name="description" class="required-entry" aria-describedby="description-details"></textarea>
                                </li>
                            </ol>
                        </div>
                        <div class="bp1-wdn-col-two-fifths">
                            <ol>
                                <li class="clear-top">
                                    <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                                </li>
                                <li>
                                    <?php echo $savvy->render($context->feed_selection); ?>
                                </li>
                            </ol>
                        </div>                                                     
                    </div>
                    <input type="submit" id="publish" name="publish" value="Publish" class="wdn-button-brand" disabled="disabled"> 
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
WDN.initializePlugin('form_validation', [function() {
    WDN.jQuery('#add_media').validation({
        containerClassName: 'validation-container',
        immediate: true
    });
}]);
</script>