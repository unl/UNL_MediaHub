<script>
    <?php if ($user->canTranscodePro()): ?>
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
$baseUrl = UNL_MediaHub_Controller::getURL();
$formView  = '';
$mediaType = 'audio';

if (isset($context->media)) {
//if we have media (we're editing) show the appropriate part of the form
    if ($context->media->isVideo()) {
        $mediaType = 'video';
    }
    $formView .= 'edit';
    // TODO: disable breadcrumbs since currently not supported in 5.0 App templates
    //$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Manage Media</a></li> <li><a href="' . $context->media->getURL() .'">'.UNL_MediaHub::escape($context->media->title).'</a></li>');
} else {
    // TODO: disable breadcrumbs since currently not supported in 5.0 App templates
    //$$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Manage Media</a></li> <li>Add Media</li></ol>');
}

$edit_caption_url = UNL_MediaHub_Manager::getURL() . '?view=editcaptions&id=' . $context->media->id;

$js = '<script type="text/javascript">
var formView = "'.$formView.'";
var mediaType = "'.$mediaType.'";
</script>
<script type="text/javascript" src="'.UNL_MediaHub_Controller::getURL().'templates/html/scripts/editMedia.js?v='.UNL_MediaHub_Controller::getVersion().'"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
';

$page->jsbody .= $js;
?>

<?php $transcoding_job = $context->media->getMostRecentTranscodingJob(); ?>
<?php $transcribing_job = $context->media->getMostRecentTranscriptionJob(); ?>

<?php if ($transcoding_job && $transcoding_job->isError()): ?>
<form id="retry_transcoding_job" method="post">
    <input type="hidden" name="media_id" value="<?php echo $context->media->id ?>">
    <input type="hidden" name="__unlmy_posttarget" value="retry_transcoding_job" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
</form>
<?php endif; ?>

<div class="dcf-pb-1">
<form class="dcf-form" action="?" method="post" name="media_form" id="media_form" enctype="multipart/form-data">

    <input id="media_url" name="url" type="hidden" value="" />
    <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
    <input type="hidden" id="id" name="id" value="<?php echo $context->media->id ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">

    <div class="dcf-bleed unl-bg-lighter-gray dcf-pt-6 dcf-bp-6">
        <div class="dcf-wrapper">
            <div class="dcf-grid" id="headline_main">
                <div class="dcf-col-100%">
                    <input type="submit" name="submit" value="Save" class="dcf-btn dcf-btn-primary dcf-float-right" />
                    <h2><div class="dcf-subhead">Edit Media Details for</div> <?php echo $context->media->title ?></h2>
                </div>
            </div>

            <?php if ($transcoding_job && $transcoding_job->isError()): ?>
                <div class="dcf-notice dcf-notice-danger" hidden>
                    <h2>Optimization Error</h2>
                    <div id="transcoding-progress">
                        <p>There was an error optimizing your media. Please delete this media record and try uploading it again.</p>
                        <button form="retry_transcoding_job" class="dcf-btn dcf-btn-inverse-secondary">Retry</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($transcoding_job && $transcoding_job->isPending()): ?>
                <?php echo $savvy->render($context, 'Feed/Media/transcoding_notice.tpl.php'); ?>
            <?php endif; ?>

            <?php if ($transcribing_job && $transcribing_job->isPending()): ?>
                <?php echo $savvy->render($context, 'Feed/Media/transcription_notice.tpl.php'); ?>
            <?php elseif(empty($context->media->media_text_tracks_id)): ?>
                <div class="dcf-notice dcf-notice-warning mh-caption-alert" hidden>
                    <h2>This Video is Missing Captions!</h2>
                    <div class="mh-caption-band">
                        <p>For accessibility reasons, captions are required for <strong>all</strong> videos.</p>
                        <p><a class="dcf-btn dcf-btn-inverse-secondary" href="<?php echo $edit_caption_url ?>">Caption Your Video</a></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$transcoding_job && !$context->media->isWebSafe()): ?>
                <div class="dcf-notice dcf-notice-warning mh-caption-alert" hidden>
                    <h2>This video might not work on the web!</h2>
                    <div class="mh-caption-band">
                        <p>This video was encoded with '<?php echo UNL_MediaHub::escape($context->media->getCodec()) ?>', which is not safe for the web, and might not work on every device/browser. Please run the video through HandBrake and swap the video out.</p>
                        <p><a class="dcf-btn dcf-btn-inverse-secondary" href="http://wdn.unl.edu/documentation/unl-mediahub/using-handbrake">How to use HandBrake</a></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$transcoding_job ||  $transcoding_job->isSuccess()): ?>
                <div class="dcf-grid dcf-col-gap-vw">
                    <div id="videoData" class="dcf-col-100% dcf-col-25%-start@sm dcf-txt-xs">
                        <h2 class="dcf-txt-h4">Set a Thumbnail</h2>
                        <ol>
                            <li>Pause the video to the left at the frame which you want as the image representation.</li>
                            <li>Click the "Set Image" button to save this as your image representation.</li>
                            <li>Continue with the form below.</li>
                        </ol>

                        <div id="imageOverlay">
                            <p>We're updating your image, this may take a few minutes depending on video length. <strong>Now is a good time to make sure the information below is up to snuff!</strong></p>
                        </div>
                        <div class="dcf-d-flex dcf-jc-center dcf-mb-3">
                            <img
                                class="dcf-w-auto dcf-h-auto dcf-w-max-100%"
                                style="max-height: 15rem;"
                                src="<?php echo $context->media->getThumbnailURL(); ?>"
                                id="thumbnail"
                                alt="Thumbnail preview"
                            />
                        </div>
                        <!-- <div id="poster_picker">
                            <a class="action" id="setImage" href="#">Set Image</a>

                        </div> -->
                        <div id="poster_picker_disabled">
                            <p>
                                The poster picker has been disabled.  Enable it by <a id="enable_poster_picker" href="#">removing the custom post image url</a>.
                            </p>
                        </div>
                    </div>
                    <div id="videoDisplay" class="dcf-col-100% dcf-col-75%-end@sm">
                        <div class="mh-iframe-wrapper">
                            <?php
                            if (isset($context->media)) {
                                echo '<iframe id="mediahub-iframe-embed" height="667" src="'.$context->media->getURL().'?format=iframe&autoplay=0" allowfullscreen title="watch media" data-mediahub-id='.$context->media->id.'></iframe>';
                            }
                            ?>
                        </div>
                        <a class="dcf-btn dcf-btn-primary mh-hide-bp2" id="setImage" href="#">Set Image</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dcf-bleed dcf-mt-6 dcf-pt-6 mh-edit-media">
        <div class="dcf-wrapper">
            <?php
                $errorNotice = new StdClass();
                $errorNotice->title = 'Media Errors';
                echo $savvy->render($errorNotice, 'ErrorListNotice.tpl.php');
            ?>
            <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-2">
                <div class="dcf-col-100% dcf-col-50%-start@md dcf-col-33%-start@lg dcf-pb-6">
                    <ol>
                        <li class="dcf-pl-2 dcf-mb-6">
                            <?php if ($transcoding_job && !$transcoding_job->isFinished()): ?>
                                <p>Swapping media is disabled while a video is being optimized.</p>
                            <?php else: ?>
                                <?php if ($transcoding_job): ?>
                                    <p>
                                        <?php
                                            echo \UNL\Templates\Icons::get(
                                                    \UNL\Templates\Icons::ICON_ALERT,
                                                    '{"size": 4}'
                                                );
                                        ?>
                                        <span class="dcf-sr-only">Notice:</span>
                                        Swapping media will cause the media to be unavailable
                                        while the upload is optimized. This upload will be optimized
                                        with the same settings as the current version.
                                    </p>
                                <?php endif; ?>
                                <?php if (!$transcoding_job): ?>
                                    <p class="dcf-txt-xs">
                                        <?php
                                            echo \UNL\Templates\Icons::get(
                                                    \UNL\Templates\Icons::ICON_ALERT,
                                                    '{"size": 4}'
                                                );
                                        ?>
                                        <span class="dcf-sr-only">Notice:</span>
                                        You MUST use HandBrake to optimize the new video.
                                        You must swap media with the same file type as you uploaded.
                                    </p>
                                <?php endif; ?>
                                <div id="mh_upload_media_container">
                                    <div class="dcf-p-3 mh-upload-box dcf-txt-center" id="mh_upload_media">
                                        <img
                                            src="<?php echo $baseUrl; ?>/templates/html/css/images/swap-arrows.svg"
                                            aria-hidden="true"
                                            alt="">
                                        <h2><span class="dcf-subhead">Swap Media</span></h2>
                                        <p>
                                            Upload a new .mp4 or .mp3 file and replace your old one.
                                            <br>
                                            <strong>
                                                <?php
                                                    echo \UNL\Templates\Icons::get(
                                                            \UNL\Templates\Icons::ICON_ALERT,
                                                            '{"size": 4}'
                                                        );
                                                    ?>
                                                (Caution: This deletes your old file.)
                                            </strong>
                                        </p>
                                    </div>
                                    <div id="filelist" class="mh-upload-box dcf-txt-center">
                                        Your browser doesn't have Flash, Silverlight or HTML5 support.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                        <li class="dcf-pl-2 dcf-mb-6">
                            <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                        </li>
                        <li class="dcf-pl-2 dcf-mb-6">
                            <?php echo $savvy->render($context->feed_selection); ?>
                        </li>
                        <li class="dcf-pl-2 dcf-mb-6" style="display:none">
                            <div class="dcf-input-checkbox">
                            <?php if($context->media->getProjection() == "equirectangular") { ?>
                                <input type="checkbox" id="projection" name="projection" checked="checked" value="equirectangular">
                            <?php } else { ?>
                                <input type="checkbox" id="projection" name="projection" value="equirectangular">
                            <?php } ?>
                                <label for="projection">360 Video (equirectangular)</label>
                            </div>
                        </li>
                        <li class="dcf-pl-2 dcf-mb-6">
                            <fieldset>
                                <legend>
                                    Poster Image
                                    <div
                                        class="dcf-popup dcf-d-inline"
                                        id="poster-details"
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
                                                    C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,
                                                    12.5C22,18.29,17.29,23,11.5,23z"></path>
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
                                                dcf-txt-md
                                                dcf-lh-4
                                            "
                                            style="min-width: 25ch;"
                                        >
                                            <p class="dcf-m-0 dcf-regular">
                                                <?php
                                                    $text = '';
                                                    if (isset($context->media) && $context->media->isVideo()) {
                                                        $text = 'This image will override the one chosen above.';
                                                    }
                                                ?>
                                                This image will be displayed as the thumbnail for the media.
                                                <?php echo $text; ?> Images should be in 16:9 ratio.
                                            </p>
                                        </div>
                                    </div>
                                </legend>
                                <?php
                                    $posterImageURLDisabled = '';
                                    $additionalOptionsDisplayClass = 'dcf-d-block';
                                ?>
                                <?php if(isset($context->media->id) && $context->media->getPosterURL()): ?>
                                    <div class="dcf-form-group">
                                        <div class="mh-channel-thumb dcf-txt-center dcf-d-flex dcf-jc-center dcf-mb-3">
                                            <img
                                                class="dcf-w-auto dcf-h-auto dcf-w-max-100%"
                                                style="max-height: 15rem;"
                                                src="<?php echo $context->media->getPosterURL(); ?>"
                                                alt="<?php
                                                    echo htmlentities($context->media->title, ENT_QUOTES);
                                                    ?> Poster Image"
                                            >
                                        </div>
                                    </div>

                                    <?php if ($context->media->isLocalPosterURL()):
                                            $additionalOptionsDisplayClass = 'dcf-d-none';
                                            $posterImageURLDisabled = 'disabled';
                                    ?>
                                    <div class="dcf-input-checkbox dcf-mb-3">
                                        <input type="checkbox" id="remove-poster-image" name="remove_poster_image" value="1">
                                        <label for="remove-poster-image">Remove</label>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="dcf-form-group">
                                    <label for="poster-image-file">Image Upload</label>
                                    <input id="poster-image-file" name="poster_image_file" type="file" />
                                    <input type="hidden" id="current-poster" name="current_poster" value="<?php echo htmlentities(@$context->media->poster, ENT_QUOTES); ?>">
                                </div>

                                <div class="<?php echo $additionalOptionsDisplayClass; ?>" id="additional-poster-options">
                                    <p class="dcf-form-group dcf-form-help">or</p>
                                    <div class="dcf-form-group">
                                        <label for="poster-image-url">URL of Image</label>
                                        <input id="poster-image-url" name="poster_image_url" <?php echo $posterImageURLDisabled; ?> type="text" class="dcf-w-100% validate-url" value="<?php echo htmlentities(@$context->media->poster, ENT_QUOTES); ?>" aria-describedby="poster-details" />
                                    </div>
                                </div>
                            </fieldset>
                        </li>
                        <li class="dcf-pl-2 dcf-mb-6">
                            <?php if (isset($context->media)): ?>
                            <a class="dcf-btn dcf-btn-primary" href="<?php echo $edit_caption_url ?>">Order/Edit Captions</a>
                            <?php endif; ?>
                        </li>
                    </ol>
                </div>

                <div class="dcf-col-100% dcf-col-50%-end@md dcf-col-67%-end@lg">
                    <fieldset id="existing_media">
                        <legend class="dcf-legend">Basic Information</legend>
                        <div class="dcf-form-group validation-container">
                            <label for="title">Title <small class="dcf-required">Required</small></label>
                            <input id="title" name="title" type="text" class="required-entry dcf-w-100%" value="<?php echo UNL_MediaHub::escape(@$context->media->title); ?>" />
                        </div>
                        <div class="dcf-form-group">
                            <label for="author">
                                Author <small class="dcf-required">Required</small> <span class="dcf-form-help">Name of media creator.</span>
                            </label>
                            <input id="author" name="author" class="required-entry dcf-w-100%" type="text" value="<?php echo UNL_MediaHub::escape(@$context->media->author); ?>" />
                        </div>
                        <div class="dcf-form-group">
                            <label for="mrss_copyright">Copyright <span class="dcf-form-help">&copy; info for media object.</span></label>
                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[10][element]" type="hidden" value="copyright"/>
                            <input class="dcf-w-100%" id="mrss_copyright" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[10][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'copyright'); ?>"/>
                        </div>
                        <div class="dcf-form-group">
                            <label for="mrss_credit">Credit</label>
                            <div class="dcf-popup dcf-d-inline" id="credit-details" data-hover="true" data-point="true">
                                <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                            S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23
                                            C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,
                                            18.29,17.29,23,11.5,23z"></path>
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
                                        Notable entity and the contribution to the creation of the media object.
                                    </p>
                                </div>
                            </div>
                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[9][element]" type="hidden" value="credit"/>
                            <input class="dcf-w-100%" id="mrss_credit" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[9][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'credit'); ?>" aria-describedby="credit-details" />
                        </div>
                        <div class="dcf-form-group">
                            <label for="mrss_category">Category</label>
                            <div
                                class="dcf-popup dcf-d-inline"
                                id="category-details"
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
                                            C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,
                                            18.29,17.29,23,11.5,23z"></path>
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
                                        Allows a taxonomy to be set that gives an indication of the type
                                        of media content, and its particular contents.
                                    </p>
                                </div>
                            </div>
                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[7][element]" type="hidden" value="category"/>
                            <input class="dcf-w-100%" id="mrss_category" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[7][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'category'); ?>" aria-describedby="category-details"/>
                        </div>
                        <div class="dcf-form-group">
                            <label for="description">
                                Description <small class="dcf-required">Required</small>
                            </label>
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
                                            C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,
                                            18.29,17.29,23,11.5,23z"></path>
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
                            <div id="description_wrapper"><textarea id="description" name="description" class="required-entry" rows="5" aria-describedby="description-details"><?php echo UNL_MediaHub::escape(@$context->media->description); ?></textarea></div>
                        </div>
                        <div class="dcf-form-group">
                            <label for="itunes_keywords">Tags</label>
                            <div class="dcf-popup dcf-d-inline" id="tag-details" data-hover="true" data-point="true">
                                <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                            S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23
                                            C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,
                                            18.29,17.29,23,11.5,23z"></path>
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
                                        A comma separated list of highly relevant keywords,
                                        MAX 10. Tags also serve as iTunes Keywords.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[4][element]" type="hidden" value="keywords"/>
                                <input class="dcf-w-100%" id="itunes_keywords" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[4][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>" aria-describedby="tag-details"/>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset
                        id="geo_location"
                        class="dcf-mt-3 dcf-collapsible-fieldset"
                        data-start-expanded="false"
                        hidden
                    >
                        <legend class="dcf-legend">Geo Location</legend>
                        <ol>
                            <li>
                                <label for="geo_lat">Latitude</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[0][element]" type="hidden" value="lat"/>
                                    <input id="geo_lat" name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[0][value]" class='geo_lat' type="text" value="<?php echo getFieldValue($context, 'geo', 'lat'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <label for="geo_long">Longitude</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[1][element]" type="hidden" value="long"/>
                                    <input id="geo_long" name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[1][value]" class='geo_long' type="text" value="<?php echo getFieldValue($context, 'geo', 'long'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <div id="map_canvas" style="width:500px;height:300px;"></div>
                            </li>
                        </ol>
                    </fieldset>

                    <?php $customFields = UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::getCustomElements(); ?>
                    <fieldset
                        class="dcf-mt-3 dcf-collapsible-fieldset"
                        id="other_header"
                        data-start-expanded="false"
                        hidden
                    >
                        <legend class="dcf-legend">Other Information</legend>
                        <ol>
                            <?php foreach ($customFields as $customField=>$description): ?>
                                <li><?php echo $savvy->render($context, 'Feed/Media/NamespacedElements/mediahub/'.$customField.'.tpl.php'); ?></li>
                            <?php endforeach; ?>
                            <li style="display:none;">
                                <label for="mrss_group">Group</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[0][element]" type="hidden" value="group"/>
                                    <input id="mrss_group" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[0][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'group'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none">
                                <!-- mrss hidden elements that are handled automatically -->
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][element]" type="hidden" value="content" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'content'); ?>" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'content'); ?>" />
                            </li>
                            <li style="display:none;">
                                <label for="mrss_rating">Rating</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[2][element]" type="hidden" value="rating"/>
                                    <input id="mrss_rating" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[2][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'rating'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_title">Title</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[3][element]" type="hidden" value="title"/>
                                    <input id="mrss_title" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[3][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'title'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_description">Description</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[4][element]" type="hidden" value="description"/>
                                    <input id="mrss_description" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[4][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'description'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_keywords">Keywords</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[5][element]" type="hidden" value="keywords"/>
                                    <input id="mrss_keywords" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[5][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'keywords'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <!-- mrss hidden elements that are handled automatically -->
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][element]" type="hidden" value="thumbnail"/>
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'thumbnail'); ?>" />
                            </li>

                            <li style="display:none;">
                                <label for="mrss_player">Player <span class="dcf-form-help">Allows the media object to be accessed through a web browser media player console.</span></label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[8][element]" type="hidden" value="player"/>
                                    <input id="mrss_player" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[8][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'player'); ?>"/>
                                </div>
                            </li>

                            <li style="display:none;">
                                <label for="mrss_restriction">Restriction <span class="dcf-form-help">Allows restrictions to be placed on the aggregator rendering the media in the feed.</span></label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[12][element]" type="hidden" value="restriction"/>
                                    <input id="mrss_restriction" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[12][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'restriction'); ?>"/>
                                </div>
                            </li>
                        </ol>
                    </fieldset>

                    <fieldset class='dcf-mt-3 dcf-collapsible-fieldset' data-start-expanded="false" hidden>
                        <legend class="dcf-legend">iTunes Information</legend>
                        <ol>
                            <li style="display:none;">
                                <label for="itunes_author">Author <span class="dcf-form-help">Name of media creator.</span></label>
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[0][element]" type="hidden" value="author"/>
                                <input id="itunes_author" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[0][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'author'); ?>"/>
                            </li>
                            <li>
                                <label for="mrss_text">
                                    Transcript/Captioning
                                </label>
                                <div
                                    class="dcf-popup dcf-d-inline"
                                    id="captioning-details"
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
                                                C5.71,23,1,18.29,1,12.5 C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,
                                                18.29,17.29,23,11.5,23z"></path>
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
                                            Allows the inclusion of a text transcript, closed captioning,
                                            or lyrics of the media content.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[11][element]" type="hidden" value="text"/>
                                    <textarea rows="3" id="mrss_text" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[11][value]" aria-describedby="captioning-details"><?php echo getFieldValue($context, 'media', 'text'); ?></textarea>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_category">Category <span class="dcf-form-help">Choose a category for use within iTunes U</span></label>
                                <div>
                                    <?php
                                    $category = '';
                                    if (isset($context->media) && $value = UNL_MediaHub_Feed_Media_NamespacedElements_itunesu::mediaHasElement($context->media->id, 'category', 'itunesu')) {
                                        $category = $value['attributes']['itunesu:code'];
                                    }
                                    ?>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunesu[0][element]" type="hidden" value="category" />
                                    <select class="dcf-input-select" id="itunes_category" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunesu[0][attributes]">
                                          <option value="">None</option>
                                          <optgroup label="Business">
                                              <option <?php if ($category == '100') echo 'selected="selected"'; ?> value="100">Business</option>
                                              <option <?php if ($category == '100100') echo 'selected="selected"'; ?> value="100100">Economics</option>
                                              <option <?php if ($category == '100101') echo 'selected="selected"'; ?> value="100101">Finance</option>
                                              <option <?php if ($category == '100102') echo 'selected="selected"'; ?> value="100102">Hospitality</option>
                                              <option <?php if ($category == '100103') echo 'selected="selected"'; ?> value="100103">Management</option>
                                              <option <?php if ($category == '100104') echo 'selected="selected"'; ?> value="100104">Marketing</option>
                                              <option <?php if ($category == '100105') echo 'selected="selected"'; ?> value="100105">Personal Finance</option>
                                              <option <?php if ($category == '100106') echo 'selected="selected"'; ?> value="100106">Real Estate</option>
                                          </optgroup>
                                          <optgroup label="Engineering">
                                              <option <?php if ($category == '101') echo 'selected="selected"'; ?> value="101">Engineering</option>
                                              <option <?php if ($category == '101100') echo 'selected="selected"'; ?> value="101100">Chemical &amp; Petroleum</option>
                                              <option <?php if ($category == '101101') echo 'selected="selected"'; ?> value="101101">Civil</option>
                                              <option <?php if ($category == '101102') echo 'selected="selected"'; ?> value="101102">Computer Science</option>
                                              <option <?php if ($category == '101103') echo 'selected="selected"'; ?> value="101103">Electrical</option>
                                              <option <?php if ($category == '101104') echo 'selected="selected"'; ?> value="101104">Environmental</option>
                                              <option <?php if ($category == '101105') echo 'selected="selected"'; ?> value="101105">Mechanical</option>
                                          </optgroup>
                                          <optgroup label="Fine Arts">
                                              <option <?php if ($category == '102') echo 'selected="selected"'; ?> value="102">Fine Arts</option>
                                              <option <?php if ($category == '102100') echo 'selected="selected"'; ?> value="102100">Architecture</option>
                                              <option <?php if ($category == '102101') echo 'selected="selected"'; ?> value="102101">Art</option>
                                              <option <?php if ($category == '102102') echo 'selected="selected"'; ?> value="102102">Art History</option>
                                              <option <?php if ($category == '102103') echo 'selected="selected"'; ?> value="102103">Dance</option>
                                              <option <?php if ($category == '102104') echo 'selected="selected"'; ?> value="102104">Film</option>
                                              <option <?php if ($category == '102105') echo 'selected="selected"'; ?> value="102105">Graphic Design</option>
                                              <option <?php if ($category == '102106') echo 'selected="selected"'; ?> value="102106">Interior Design</option>
                                              <option <?php if ($category == '102107') echo 'selected="selected"'; ?> value="102107">Music</option>
                                              <option <?php if ($category == '102108') echo 'selected="selected"'; ?> value="102108">Theater</option>
                                          </optgroup>
                                          <optgroup label="Health &amp; Medicine">
                                              <option <?php if ($category == '103') echo 'selected="selected"'; ?> value="103">Health &amp; Medicine</option>
                                              <option <?php if ($category == '103100') echo 'selected="selected"'; ?> value="103100">Anatomy &amp; Physiology</option>
                                              <option <?php if ($category == '103101') echo 'selected="selected"'; ?> value="103101">Behavioral Science</option>
                                              <option <?php if ($category == '103102') echo 'selected="selected"'; ?> value="103102">Dentistry</option>
                                              <option <?php if ($category == '103103') echo 'selected="selected"'; ?> value="103103">Diet &amp; Nutrition</option>
                                              <option <?php if ($category == '103104') echo 'selected="selected"'; ?> value="103104">Emergency</option>
                                              <option <?php if ($category == '103105') echo 'selected="selected"'; ?> value="103105">Genetics</option>
                                              <option <?php if ($category == '103106') echo 'selected="selected"'; ?> value="103106">Gerontology</option>
                                              <option <?php if ($category == '103107') echo 'selected="selected"'; ?> value="103107">Health &amp; Exercise Science</option>
                                              <option <?php if ($category == '103108') echo 'selected="selected"'; ?> value="103108">Immunology</option>
                                              <option <?php if ($category == '103109') echo 'selected="selected"'; ?> value="103109">Neuroscience</option>
                                              <option <?php if ($category == '103110') echo 'selected="selected"'; ?> value="103110">Pharmacology &amp; Toxicology</option>
                                              <option <?php if ($category == '103111') echo 'selected="selected"'; ?> value="103111">Psychiatry</option>
                                              <option <?php if ($category == '103112') echo 'selected="selected"'; ?> value="103112">Public Health</option>
                                              <option <?php if ($category == '103113') echo 'selected="selected"'; ?> value="103113">Radiology</option>
                                          </optgroup>
                                          <optgroup label="History">
                                              <option <?php if ($category == '104') echo 'selected="selected"'; ?> value="104">History</option>
                                              <option <?php if ($category == '104100') echo 'selected="selected"'; ?> value="104100">Ancient</option>
                                              <option <?php if ($category == '104101') echo 'selected="selected"'; ?> value="104101">Medieval</option>
                                              <option <?php if ($category == '104102') echo 'selected="selected"'; ?> value="104102">Military</option>
                                              <option <?php if ($category == '104103') echo 'selected="selected"'; ?> value="104103">Modern</option>
                                              <option <?php if ($category == '104104') echo 'selected="selected"'; ?> value="104104">African</option>
                                              <option <?php if ($category == '104105') echo 'selected="selected"'; ?> value="104105">Asian</option>
                                              <option <?php if ($category == '104106') echo 'selected="selected"'; ?> value="104106">European</option>
                                              <option <?php if ($category == '104107') echo 'selected="selected"'; ?> value="104107">Middle Eastern</option>
                                              <option <?php if ($category == '104108') echo 'selected="selected"'; ?> value="104108">North American</option>
                                              <option <?php if ($category == '104109') echo 'selected="selected"'; ?> value="104109">South American</option>
                                          </optgroup>
                                          <optgroup label="Humanities">
                                              <option <?php if ($category == '105') echo 'selected="selected"'; ?> value="105">Humanities</option>
                                              <option <?php if ($category == '105100') echo 'selected="selected"'; ?> value="105100">Communications</option>
                                              <option <?php if ($category == '105101') echo 'selected="selected"'; ?> value="105101">Philosophy</option>
                                              <option <?php if ($category == '105102') echo 'selected="selected"'; ?> value="105102">Religion</option>
                                          </optgroup>
                                          <optgroup label="Language">
                                              <option <?php if ($category == '106') echo 'selected="selected"'; ?> value="106">Language</option>
                                              <option <?php if ($category == '106100') echo 'selected="selected"'; ?> value="106100">African</option>
                                              <option <?php if ($category == '106101') echo 'selected="selected"'; ?> value="106101">Ancient</option>
                                              <option <?php if ($category == '106102') echo 'selected="selected"'; ?> value="106102">Asian</option>
                                              <option <?php if ($category == '106103') echo 'selected="selected"'; ?> value="106103">Eastern European/Slavic</option>
                                              <option <?php if ($category == '106104') echo 'selected="selected"'; ?> value="106104">English</option>
                                              <option <?php if ($category == '106105') echo 'selected="selected"'; ?> value="106105">English Language Learners</option>
                                              <option <?php if ($category == '106106') echo 'selected="selected"'; ?> value="106106">French</option>
                                              <option <?php if ($category == '106107') echo 'selected="selected"'; ?> value="106107">German</option>
                                              <option <?php if ($category == '106108') echo 'selected="selected"'; ?> value="106108">Italian</option>
                                              <option <?php if ($category == '106109') echo 'selected="selected"'; ?> value="106109">Linguistics</option>
                                              <option <?php if ($category == '106110') echo 'selected="selected"'; ?> value="106110">Middle Eastern</option>
                                              <option <?php if ($category == '106111') echo 'selected="selected"'; ?> value="106111">Spanish &amp; Portuguese</option>
                                              <option <?php if ($category == '106112') echo 'selected="selected"'; ?> value="106112">Speech Pathology</option>
                                          </optgroup>
                                          <optgroup label="Literature">
                                              <option <?php if ($category == '1071') echo 'selected="selected"'; ?> value="107">Literature</option>
                                              <option <?php if ($category == '107100') echo 'selected="selected"'; ?> value="107100">Anthologies</option>
                                              <option <?php if ($category == '107101') echo 'selected="selected"'; ?> value="107101">Biography</option>
                                              <option <?php if ($category == '107102') echo 'selected="selected"'; ?> value="107102">Classics</option>
                                              <option <?php if ($category == '107103') echo 'selected="selected"'; ?> value="107103">Criticism</option>
                                              <option <?php if ($category == '107104') echo 'selected="selected"'; ?> value="107104">Fiction</option>
                                              <option <?php if ($category == '107105') echo 'selected="selected"'; ?> value="107105">Poetry</option>
                                          </optgroup>
                                          <optgroup label="Mathematics">
                                              <option <?php if ($category == '108') echo 'selected="selected"'; ?> value="108">Mathematics</option>
                                              <option <?php if ($category == '108100') echo 'selected="selected"'; ?> value="108100">Advanced Mathematics</option>
                                              <option <?php if ($category == '108101') echo 'selected="selected"'; ?> value="108101">Algebra</option>
                                              <option <?php if ($category == '108102') echo 'selected="selected"'; ?> value="108102">Arithmetic</option>
                                              <option <?php if ($category == '108103') echo 'selected="selected"'; ?> value="108103">Calculus</option>
                                              <option <?php if ($category == '108104') echo 'selected="selected"'; ?> value="108104">Geometry</option>
                                              <option <?php if ($category == '108105') echo 'selected="selected"'; ?> value="108105">Statistics</option>
                                          </optgroup>
                                          <optgroup label="Science">
                                              <option <?php if ($category == '109') echo 'selected="selected"'; ?> value="109">Science</option>
                                              <option <?php if ($category == '109100') echo 'selected="selected"'; ?> value="109100">Agricultural</option>
                                              <option <?php if ($category == '109101') echo 'selected="selected"'; ?> value="109101">Astronomy</option>
                                              <option <?php if ($category == '109102') echo 'selected="selected"'; ?> value="109102">Atmospheric</option>
                                              <option <?php if ($category == '109103') echo 'selected="selected"'; ?> value="109103">Biology</option>
                                              <option <?php if ($category == '109104') echo 'selected="selected"'; ?> value="109104">Chemistry</option>
                                              <option <?php if ($category == '109105') echo 'selected="selected"'; ?> value="109105">Ecology</option>
                                              <option <?php if ($category == '109106') echo 'selected="selected"'; ?> value="109106">Geography</option>
                                              <option <?php if ($category == '109107') echo 'selected="selected"'; ?> value="109107">Geology</option>
                                              <option <?php if ($category == '109108') echo 'selected="selected"'; ?> value="109108">Physics</option>
                                          </optgroup>
                                          <optgroup label="Social Science">
                                              <option <?php if ($category == '110') echo 'selected="selected"'; ?> value="110">Social Science</option>
                                              <option <?php if ($category == '110100') echo 'selected="selected"'; ?> value="110100">Law</option>
                                              <option <?php if ($category == '110101') echo 'selected="selected"'; ?> value="110101">Political Science</option>
                                              <option <?php if ($category == '110102') echo 'selected="selected"'; ?> value="110102">Public Administration</option>
                                              <option <?php if ($category == '110103') echo 'selected="selected"'; ?> value="110103">Psychology</option>
                                              <option <?php if ($category == '110104') echo 'selected="selected"'; ?> value="110104">Social Welfare</option>
                                              <option <?php if ($category == '110105') echo 'selected="selected"'; ?> value="110105">Sociology</option>
                                          </optgroup>
                                          <optgroup label="Society">
                                              <option <?php if ($category == '111') echo 'selected="selected"'; ?> value="111">Society</option>
                                              <option <?php if ($category == '111100') echo 'selected="selected"'; ?> value="111100">African-American Studies</option>
                                              <option <?php if ($category == '111101') echo 'selected="selected"'; ?> value="111101">Asian Studies</option>
                                              <option <?php if ($category == '111102') echo 'selected="selected"'; ?> value="111102">European &amp; Russian Studies</option>
                                              <option <?php if ($category == '111103') echo 'selected="selected"'; ?> value="111103">Indigenous Studies</option>
                                              <option <?php if ($category == '111104') echo 'selected="selected"'; ?> value="111104">Latin &amp; Caribbean Studies</option>
                                              <option <?php if ($category == '111105') echo 'selected="selected"'; ?> value="111105">Middle Eastern Studies</option>
                                              <option <?php if ($category == '111106') echo 'selected="selected"'; ?> value="111106">Women's Studies</option>
                                          </optgroup>
                                          <optgroup label="Teaching &amp; Education">
                                              <option <?php if ($category == '112') echo 'selected="selected"'; ?> value="112">Teaching &amp; Education</option>
                                              <option <?php if ($category == '112100') echo 'selected="selected"'; ?> value="112100">Curriculum &amp; Teaching</option>
                                              <option <?php if ($category == '112101') echo 'selected="selected"'; ?> value="112101">Educational Leadership</option>
                                              <option <?php if ($category == '112102') echo 'selected="selected"'; ?> value="112102">Family &amp; Childcare</option>
                                              <option <?php if ($category == '112103') echo 'selected="selected"'; ?> value="112103">Learning Resources</option>
                                              <option <?php if ($category == '112104') echo 'selected="selected"'; ?> value="112104">Psychology &amp; Research</option>
                                              <option <?php if ($category == '112105') echo 'selected="selected"'; ?> value="112105">Special Education</option>
                                          </optgroup>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_block">Block from iTunes <span class="dcf-form-help">Set to 'yes' if you would like to block this element from iTunes</span></label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[1][element]" type="hidden" value="block"/>
                                    <select class="dcf-input-select" id="itunes_block" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[1][value]">
                                          <?php
                                          if (getFieldValue($context, 'itunes', 'block') == "yes") {
                                              echo '<option value="">No</option><option value="yes" selected="selected">Yes</option>';
                                          } else {
                                              echo '<option value="">No</option><option value="yes">Yes</option>';
                                          }
                                          ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_duration">Duration (HH:MM:SS)</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[2][element]" type="hidden" value="duration"/>
                                    <input id="itunes_duration" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[2][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'duration'); ?>"/>
                                </div>
                                <div class="dcf-pt-2">
                                  <button class="dcf-btn dcf-btn-primary find-duration">Find The duration</button>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="itunes_explicit">Explicit</label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[3][element]" type="hidden" value="explicit"/>
                                    <input id="itunes_explicit" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[3][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'explicit'); ?>"/>
                                </div>
                            </li>

                            <li>
                                <label for="itunes_subtitle">Subtitle <span class="dcf-form-help">The contents of this tag are shown in the Description column in iTunes. The subtitle displays best if it is only a few words long.</span></label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[5][element]" type="hidden" value="subtitle"/>
                                    <input id="itunes_subtitle" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[5][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_summary">Summary <span class="dcf-form-help">The contents of this tag are shown in a separate window that appears when the "circled i" in the Description column is clicked.</span></label>
                                <div>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[6][element]" type="hidden" value="summary"/>
                                    <input id="itunes_summary" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[6][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'summary'); ?>"/>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                </div>
                <script>
                    window.addEventListener('inlineJSReady', function() {
                        WDN.initializePlugin('collapsible-fieldsets');
                    }, false);
                </script>

                <?php
                function getFieldValue($savant, $xmlns, $element)
                {
                    if (isset($savant->media)) {
                        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns;
                        if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                            return UNL_MediaHub::escape($element->value);
                        }
                    }
                    return '';
                }
                function getFieldAttributes($savant, $xmlns, $element)
                {
                    if (isset($savant->media)) {
                        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns;
                        if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                            return UNL_MediaHub::escape(serialize($element->attributes));
                        }
                    }
                    return '';
                }
                ?>
            </div>
            <div class="dcf-mt-4 dcf-mb-6">
                <input type="submit" name="submit" id="continue3" value="Save" class="dcf-btn dcf-btn-primary dcf-float-left" />
                <button id="delete-media" class="dcf-btn dcf-btn-primary">Delete</button>
                <a
                    rel="noopener"
                    href="<?php echo htmlentities($context->media->getURL().'/download', ENT_QUOTES); ?>"
                    target="_blank"
                    class="dcf-btn dcf-btn-secondary"
                >Download Media</a>
            </div>
        </div>
    </div>
</form>
<?php echo $savvy->render($context->media, 'Media/DeleteForm.tpl.php'); ?>
</div>

<?php
    $page->addScriptDeclaration("
        var removePosterCheckbox = document.getElementById('remove-poster-image');
        var mediaForm = document.getElementById('media_form');
        var postImageUrlInput = document.getElementById('poster-image-url');
        var currentPosterInput = document.getElementById('current-poster');
        var additionalPosterOptionsDiv = document.getElementById('additional-poster-options');

        if (removePosterCheckbox) {
            removePosterCheckbox.addEventListener('change', function() {
                if (removePosterCheckbox.checked) {
                    additionalPosterOptionsDiv.classList.remove('dcf-d-none');
                    additionalPosterOptionsDiv.classList.add('dcf-d-block');
                    postImageUrlInput.removeAttribute('disabled');
                    postImageUrlInput.value= '';
                } else {
                    additionalPosterOptionsDiv.classList.remove('dcf-d-block');
                    additionalPosterOptionsDiv.classList.add('dcf-d-none');
                    postImageUrlInput.setAttribute('disabled', '');
                    postImageUrlInput.value = currentPosterInput.value;
                }
            });
        }

        if (mediaForm) {
            mediaForm.addEventListener('submit', function() {
                postImageUrlInput.removeAttribute('disabled');
            });
        }
    ");
?>
