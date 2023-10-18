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
        <h2>Manage Media</h2>
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
                                <div
                                    class="dcf-popup dcf-d-inline"
                                    id="author-details"
                                    data-hover="true"
                                    data-point="true"
                                >
                                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23
                                                C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,
                                                22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5
                                                C8.224,19,8,19.224,8,19.5 S8.224,20,8.5,20h6c0.276,
                                                0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                                            <circle cx="11" cy="6.5" r="1"></circle>
                                            <g>
                                                <path fill="none" d="M0 0H24V24H0z"></path>
                                            </g>
                                        </svg>
                                    </button>
                                    <div
                                        class="
                                            dcf-popup-content
                                            unl-cream
                                            unl-bg-blue
                                            dcf-p-1
                                            dcf-rounded
                                        "
                                        style="min-width: 25ch;"
                                    >
                                        <p class="dcf-m-0 dcf-regular">
                                        Name of media creator
                                        </p>
                                    </div>
                                </div>
                                <input type="text" id="author" name="author" class="required-entry" aria-describedby="author-details">
                            </div>
                            <div class="dcf-form-group">
                                <label for="description">Description <small class="dcf-required">Required</small></label>
                                <div
                                    class="dcf-popup dcf-d-inline"
                                    id="description-details"
                                    data-hover="true"
                                    data-point="true"
                                >
                                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23
                                                C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,
                                                22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5
                                                C8.224,19,8,19.224,8,19.5 S8.224,20,8.5,20h6c0.276,
                                                0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                                            <circle cx="11" cy="6.5" r="1"></circle>
                                            <g>
                                                <path fill="none" d="M0 0H24V24H0z"></path>
                                            </g>
                                        </svg>
                                    </button>
                                    <div
                                        class="
                                            dcf-popup-content
                                            unl-cream
                                            unl-bg-blue
                                            dcf-p-1
                                            dcf-rounded
                                        "
                                        style="min-width: 25ch;"
                                    >
                                        <p class="dcf-m-0 dcf-regular">
                                            Explain what this media is all about. Use a few sentences,
                                            but keep it to 1 paragraph.
                                        </p>
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

                    <fieldset>
                        <legend>Auto Captions</legend>
                        <div class="dcf-input-checkbox">
                            <input id="opt-out-captions" name="opt-out-captions" type="checkbox" value="1">
                            <label for="opt-out-captions">Opt Out of auto captions</label>
                            <p class="dcf-form-help dcf-mb-0" id="opt-out-captions">
                                You would only want to opt out if your video is not in english
                                or you already have captions for your video. Disregard if your uploading audio.
                            </p>
                        </div>
                    </fieldset>
                    <p class="dcf-txt-sm">
                        Note: All videos will be optimized for web in multiple resolutions automatically.
                        If you are uploading an audio file, this will have no effect.
                    </p>

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