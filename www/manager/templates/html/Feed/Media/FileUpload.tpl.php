<script>
    <?php if ($user->canTranscode()): ?>
        const MAX_UPLOAD = "<?php echo UNL_MediaHub_Controller::$max_upload_mb*10 ?>";
        const VALID_VIDEO_EXTNESIONS = "mp4,mov";
    <?php else: ?>
        const MAX_UPLOAD = "<?php echo UNL_MediaHub_Controller::$max_upload_mb ?>";
        const VALID_VIDEO_EXTNESIONS = "mp4";
    <?php endif ?>
</script>

<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js?v='.UNL_MediaHub_Controller::getVersion());
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/uploadScript.js?v='.UNL_MediaHub_Controller::getVersion());
?>

<div class="dcf-bleed unl-bg-lightest-gray mh-upload-band dcf-pb-6 dcf-pt-6">
    <div class="dcf-wrapper">
        <h2>
            Manage Media
            <span class="dcf-subhead dcf-float-right"><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></span>
        </h2>
        <form action="?" method="post" id="add_media">
            <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
            <input type="hidden" id="media_url" name="url" value="">
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
            <div class="dcf-grid-halves@sm dcf-col-gap-6">
                <div id="mh_upload_media_container">
                    <div id="mh_upload_media" class="mh-upload-box dcf-txt-center">
                        <h3 class="dcf-txtd-h2">+<span class="dcf-subhead">Add Media</span></h3>
                        <p>.mp4 or .mp3<br>(Maximum file size: <?php echo UNL_MediaHub_Controller::$max_upload_mb; ?>mb)</p>
                    </div>
                    <div id="filelist" class="mh-upload-box dcf-txt-center">
                        Your browser doesn't have Flash, Silverlight or HTML5 support.
                    </div>
                </div>
                <div>
                    <div class="dcf-grid-halves@sm dcf-col-gap-4">
                        <div>
                            <ol>
                                <li class="clear-top">
                                    <label class="dcf-label" for="title">
                                        Title
                                        <span class="dcf-required">*</span>
                                    </label>
                                    <input class="dcf-input-text" type="text" id="title" name="title" class="required-entry">
                                </li>
                                <li>
                                    <label class="dcf-label" for="author">
                                        Author
                                        <span class="dcf-required">*</span>
                                    </label>
                                    <div class="mh-tooltip wdn-icon-info italic hang-right" id="author-details">
                                        <div>
                                            Name of media creator
                                        </div>
                                    </div>
                                    <input class="dcf-input-text" type="text" id="author" name="author" class="required-entry" aria-describedby="author-details">
                                </li>
                                <li>
                                    <label class="dcf-label" for="description">
                                        Description
                                        <span class="dcf-required">*</span>
                                    </label>
                                    <div class="mh-tooltip wdn-icon-info italic" id="description-details">
                                        <div>
                                            Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.
                                        </div>
                                    </div>
                                    <textarea class="dcf-input-text" rows="4" type="text" id="description" name="description" class="required-entry" aria-describedby="description-details"></textarea>
                                </li>
                            </ol>
                        </div>
                        <div>
                            <ol>
                                <li class="clear-top">
                                    <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                                </li>
                                <li>
                                    <?php echo $savvy->render($context->feed_selection); ?>
                                </li>
                                <li style="display:none">
                                    <div class="mh-tooltip invisible" id="autodetect-360">
                                    </div>
                                    <input class="dcf-input-control" type="checkbox" id="projection" name="projection" value="equirectangular">
                                    <label class="dcf-label" for="projection">360 Video (equirectangular)</label>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <?php if ($user->canTranscodePro()): ?>
                        <div>
                            <fieldset class="optimization-settings">
                                <legend class="dcf-legend">Optimization settings</legend>
                                <p>Only videos will be optimized. If you are uploading an audio file, these settings will have no effect.</p>
                                <ol>
                                    <li>
                                        <label class="dcf-label"><input type="radio" name="optimization" value="none" />None (video is already optimized with HandBrake presets)</label>
                                    </li>
                                    <li>
                                        <label class="dcf-label"><input type="radio" name="optimization" value="mp4" />Single file at quarter HD (540p, use this to reduce cost)</label>
                                    </li>
                                    <li>
                                        <label class="dcf-label"><input type="radio" name="optimization" value="hls" checked="checked" />Multiple files to optimize video quality (480p, 540p, 720p, and 1080p)</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    <?php elseif ($user->canTranscode()): ?>
                        <div>
                            <fieldset class="optimization-settings">
                                <legend class="dcf-legend">Optimization settings</legend>
                                <p>Only videos will be optimized. If you are uploading an audio file, these settings will have no effect. <a href="https://wdn.unl.edu/mediahub-video-optimization-inquiry">If you need more options, please contact us</a>.</p>
                                <ol class="dcf-form-group">
                                    <li>
                                        <label class="dcf-label"><input class="dcf-input-control" type="radio" name="optimization" value="none" />None (video is already optimized with HandBrake presets)</label>
                                    </li>
                                    <li>
                                        <label class="dcf-label"><input class="dcf-input-control" type="radio" name="optimization" value="mp4" checked="checked" />Single file at quarter HD</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    <?php endif; ?>
                    
                    <input type="submit" id="publish" name="publish" value="Next Step: Add Captions" class="dcf-btn dcf-btn-primary" disabled="disabled">
                    <?php if (UNL_MediaHub_Controller::$caption_requirement_date):?>
                        <p class="wdn-icon wdn-icon-attention">
                            Note: Media will not be published until it is captioned.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$page->addScriptDeclaration("WDN.initializePlugin('form_validation', [function($) {
    $('#add_media').validation({
        containerClassName: 'validation-container',
        immediate: true
    });
}]);");
?>