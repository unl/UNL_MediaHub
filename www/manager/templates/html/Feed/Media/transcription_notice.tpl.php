<div class="dcf-notice dcf-notice-info" hidden>
    <h2>We are captioning your media</h2>
    <div id="ai-caption-progress" aria-live="polite">
        <p class="dcf-mb-1">
            We are captioning your media. This may take some time,
            so please fill out the media details while you wait.
        </p>
        <p class="transcription_queue_data dcf-d-none! dcf-mb-3">Queue Position: <span class="transcription_queue_position">-1</span> of <span class="transcription_queue_length">-1</span></p>
        <progress>loading</progress>
    </div>
</div>

<?php
$transcribe_status_api = UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json";

$page->addScriptDeclaration("
    window.mediahub = window.mediahub ?? {};
    window.mediahub.transcribe_status_api = '" . $transcribe_status_api . "';
");
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/manager_transcribe_notice.js?v='.UNL_MediaHub_Controller::getVersion());
?>
