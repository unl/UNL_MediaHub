<div class="dcf-notice dcf-notice-info" hidden>
    <h2>We are captioning your video</h2>
    <div id="ai-caption-progress" aria-live="polite">
        <p>We are captioning your video. This may take some time, so please fill out the media details while you wait.</p>
        <progress>loading</progress>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    require(['jquery'], function($) {
        var checkStatus = function() {
            \$.get('" . UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json', function(data) {
                if (data.transcription_is_complete) {
                    var \$message = $('#ai-caption-progress');
                    \$message.html('<p>We have finished preparing your captions. <a href=\"'+window.location+'\">Reload this page</a> and activate your captions.</p>');
                    \$message.closest('.dcf-notice').removeClass('dcf-notice-info').addClass('dcf-notice-success');
                } else {
                    //Try again in 10 seconds
                    setTimeout(checkStatus, 10000);
                }
            });
        };

        checkStatus();
    });");
?>
