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
        <?php
            $errorNotice = new StdClass();
            $errorNotice->title = 'Media Errors';
            echo $savvy->render($errorNotice, 'ErrorListNotice.tpl.php');
        ?>
        <form class="dcf-form" action="?" method="post" id="add_media">
            <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
            <input type="hidden" id="media_url" name="url" value="">
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
            <div class="dcf-grid-halves@sm dcf-col-gap-vw">
                <div id="mh_upload_media_container">
                    <div class="dcf-ratio dcf-ratio-16x9 mh-upload-box" id="mh_upload_media">
                        <div class="dcf-ratio-child dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-txt-center">
                            <span>Add Media</span>
                            <p class="dcf-mb-0">
                                <?php if ($user->canTranscode()): ?>
                                    .mp4, .mov, or .mp3
                                    <br>
                                    (Maximum file size: <?php echo UNL_MediaHub_Controller::$max_upload_mb * 10; ?>MB
                                    or <?php echo number_format(
                                        ((UNL_MediaHub_Controller::$max_upload_mb * 10) / 1000),
                                        2,
                                        '.',
                                        ','
                                    ); ?>GB)
                                <?php else: ?>
                                    .mp4 or .mp3
                                    <br>
                                    (Maximum file size: <?php echo UNL_MediaHub_Controller::$max_upload_mb; ?>MB
                                    or <?php echo number_format(
                                        (UNL_MediaHub_Controller::$max_upload_mb / 1000),
                                        2,
                                        '.',
                                        ','
                                    ); ?>GB)
                                <?php endif ?>
                            </p>
                        </div>
                    </div>
                    <div id="filelist" class="mh-upload-box dcf-txt-center">
                        Your browser doesn't have Flash, Silverlight or HTML5 support.
                    </div>
                </div>
                <div>
                    <div class="dcf-grid-halves@sm dcf-col-gap-vw">
                        <div>
                            <div class="dcf-form-group">
                                <label for="title">Title <small class="dcf-required">Required</small></label>
                                <input type="text" id="title" name="title" class="required-entry">
                            </div>
                            <div class="dcf-form-group">
                                <label for="author">Author <small class="dcf-required">Required</small></label>
                                <div class="mh-tooltip hang-right" id="author-details">
                                    <?php echo $savvy->render('author tooltip', 'InfoIcon.tpl.php'); ?>
                                    <div>
                                        Name of media creator
                                    </div>
                                </div>
                                <input type="text" id="author" name="author" class="required-entry" aria-describedby="author-details">
                            </div>
                            <div class="dcf-form-group">
                                <label for="description">Description <small class="dcf-required">Required</small></label>
                                <div class="mh-tooltip" id="description-details">
                                    <?php echo $savvy->render('description tooltip', 'InfoIcon.tpl.php'); ?>
                                    <div>
                                        Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.
                                    </div>
                                </div>
                                <textarea rows="4" type="text" id="description" name="description" class="required-entry" aria-describedby="description-details"></textarea>
                            </div>
                        </div>
                        <div>
                            <div class="dcf-form-group">
                                <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                            </div>
                            <div class="dcf-form-group">
                                <?php echo $savvy->render($context->feed_selection); ?>
                            </div>
                            <div class="dcf-form-group" style="display:none">
                                <div class="mh-tooltip invisible" id="autodetect-360"></div>
                                <div class="dcf-input-checkbox">
                                    <input type="checkbox" id="projection" name="projection" value="equirectangular">
                                    <label for="projection">360 Video (equirectangular)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($user->canTranscodePro()): ?>
                        <div>
                            <fieldset>
                                <legend class="dcf-legend">Optimization Settings</legend>
                                <p class="dcf-txt-xs">
                                    Only videos will be optimized and converted to .mp4 files. If you are
                                    uploading an audio file, these settings will have no effect.
                                </p>
                                <div>
                                    <div class="dcf-input-radio">
                                        <input id="optimization-1" type="radio" name="optimization" value="none" />
                                        <label for="optimization-1">
                                            None (video is already optimized with HandBrake presets, this should be an .mp4 for maximum compatibility)
                                        </label>
                                    </div>
                                    <div class="dcf-input-radio">
                                        <input id="optimization-2" type="radio" name="optimization" value="mp4" /><label for="optimization-2">Single file at quarter HD (540p, use this to reduce cost)</label>
                                    </div>
                                    <div class="dcf-input-radio">
                                        <input id="optimization-3" type="radio" name="optimization" value="hls" checked="checked" /><label for="optimization-3">Multiple files to optimize video quality (480p, 540p, 720p, and 1080p)</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    <?php elseif ($user->canTranscode()): ?>
                        <div>
                            <fieldset>
                                <legend class="dcf-legend">Optimization Settings</legend>
                                <p class="dcf-txt-xs">
                                    Only videos will be optimized and converted to .mp4 files. If you are uploading an audio file,
                                    these settings will have no effect.
                                    <a href="https://wdn.unl.edu/mediahub-video-optimization-inquiry">If you need
                                        more options, please contact us</a>.
                                </p>
                                <div>
                                    <div class="dcf-input-radio">
                                        <input
                                            id="optimization-1"
                                            class="dcf-input-control"
                                            type="radio"
                                            name="optimization"
                                            value="none"
                                        />
                                        <label for="optimization-1">
                                            None (video is already optimized with HandBrake presets, this should be an .mp4 for maximum compatibility)
                                        </label>
                                    </div>
                                        <div class="dcf-input-radio">
                                        <input id="optimization-2" class="dcf-input-control" type="radio" name="optimization" value="mp4" checked="checked" /><label for="optimization-2">Single file at quarter HD</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    <?php endif; ?>

                    <input type="submit" id="publish" name="publish" value="Next Step: Add Captions" class="dcf-btn dcf-btn-primary dcf-mt-3" disabled="disabled">
                    <?php if (UNL_MediaHub_Controller::$caption_requirement_date):?>
                        <p>
                            <?php echo $savvy->render('publish tooltip', 'InfoIcon.tpl.php'); ?>
                            Note: Media will not be published until it is captioned.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    document.getElementById('add_media').addEventListener('submit', function(e) {
        e.preventDefault();
        var submitBtn = document.getElementById('publish');
        submitBtn.setAttribute('disabled', 'disabled');
        var errors = [];

        // Validate Title
        var title = document.getElementById('title').value.trim();
        if (!title) {
            errors.push('Title is required.');
        }

        // Validate Author
        var author = document.getElementById('author').value.trim();
        if (!author) {
            errors.push('Author is required.');
        }

        // Validate Description
        var description = document.getElementById('description').value.trim();
        if (!description) {
            errors.push('Description is required.');
        }

        // Validate Privacy
        var privacy = document.getElementById('privacy').value.trim();
        if (!privacy) {
            errors.push('Privacy is required.');
        }

        // Validate Feed
        var feedChecked = false;
        var newFeed = document.getElementById('new_feed').value.trim();
        var feeds = document.getElementsByName('feed_id[]');
        for (var i=0; i<feeds.length; i++) {
            if (feeds[i].checked === true) {
                feedChecked = true;
                break;
             }
        }
        if (!feedChecked && !newFeed) {
            errors.push('Media must have at least one Channel.');
        }

        // Validate Optimization
        var optimization = document.getElementsByName('optimization');
        if (!optimization) {
            errors.push('Optimization is required.');
        }

        // Submit form or display errors
        if (errors.length == 0) {
            this.submit();
        } else {
            var mediaErrorsContainer = document.getElementById('media-errors');
            var mediaErrorsList = document.getElementById('media-errors-list');
            if (mediaErrorsList) {
                mediaErrorsList.innerHTML = '';
                for (var i=0; i<errors.length; i++) {
                    var errorItem = document.createElement('li');
                    errorItem.innerHTML = errors[i];
                    mediaErrorsList.appendChild(errorItem);
                }
            }
            submitBtn.removeAttribute('disabled');
            mediaErrorsContainer.style.display = 'block';
        }
    });
");
?>