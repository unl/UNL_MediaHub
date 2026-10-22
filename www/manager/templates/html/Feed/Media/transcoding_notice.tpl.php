<div class="dcf-notice dcf-notice-info" hidden>
    <h2>We are optimizing your video</h2>
    <div id="transcoding-progress" aria-live="polite">
        <p>We are optimizing your video for the web. This may take some time, so please fill out the media details.</p>
        <progress>loading</progress>
    </div>
</div>

<?php
$transcoding_status_api = UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json";
$add_media_url = UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id;

$page->addScriptDeclaration("
    window.mediahub = window.mediahub ?? {};
    window.mediahub.transcoding_status_api = '" . $transcoding_status_api . "';
    window.mediahub.add_media_url = '" . $add_media_url . "';
");
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/manager_transcode_notice.js?v='.UNL_MediaHub_Controller::getVersion());
?>
