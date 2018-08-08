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

<div class="wdn-band wdn-light-neutral-band mh-upload-band">
    <div class="wdn-inner-wrapper">
        <h1 class="wdn-brand clear-top">
            Manage Media
            <span class="wdn-subhead wdn-pull-right"><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></span>
        </h1>
        <form action="?" method="post" id="add_media">
            <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
            <input type="hidden" id="media_url" name="url" value="">
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
            <div class="wdn-grid-set">
                <div id="mh_upload_media_container" class="bp2-wdn-col-three-sevenths">
                    <div id="mh_upload_media" class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">Add Media</span></h2>
                        <p>.mp4 or .mp3<br>(Maximum file size: <?php echo UNL_MediaHub_Controller::$max_upload_mb; ?>mb)</p>
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
                                <li style="display:none">
                                    <div class="mh-tooltip invisible" id="autodetect-360">
                                    </div>
                                    <input type="checkbox" id="projection" name="projection" value="equirectangular">
                                    <label for="projection">360 Video (equirectangular)</label>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <?php if ($user->canTranscodePro()): ?>
                        <div>
                            <fieldset class="optimization-settings">
                                <legend>Optimization settings</legend>
                                <p>Only videos will be optimized. If you are uploading an audio file, these settings will have no effect.</p>
                                <ol>
                                    <li>
                                        <label><input type="radio" name="optimization" value="none" />None (video is already optimized with HandBrake presets)</label>
                                    </li>
                                    <li>
                                        <label><input type="radio" name="optimization" value="mp4" />Single file at quarter HD (540p, use this to reduce cost)</label>
                                    </li>
                                    <li>
                                        <label><input type="radio" name="optimization" value="hls" checked="checked" />Multiple files to optimize video quality (480p, 540p, 720p, and 1080p)</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    <?php elseif ($user->canTranscode()): ?>
                        <div>
                            <fieldset class="optimization-settings">
                                <legend>Optimization settings</legend>
                                <p>Only videos will be optimized. If you are uploading an audio file, these settings will have no effect. <a href="https://wdn.unl.edu/mediahub-video-optimization-inquiry">If you need more options, please contact us</a>.</p>
                                <ol>
                                    <li>
                                        <label><input type="radio" name="optimization" value="none" />None (video is already optimized with HandBrake presets)</label>
                                    </li>
                                    <li>
                                        <label><input type="radio" name="optimization" value="mp4" checked="checked" />Single file at quarter HD</label>
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    <?php endif; ?>
                    
                    <input type="submit" id="publish" name="publish" value="Next Step: Add Captions" class="wdn-button-brand" disabled="disabled"> 
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

<script type="text/javascript">
WDN.initializePlugin('form_validation', [function($) {
    $('#add_media').validation({
        containerClassName: 'validation-container',
        immediate: true
    });
}]);
</script>