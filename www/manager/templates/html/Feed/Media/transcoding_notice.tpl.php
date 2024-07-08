<div class="dcf-notice dcf-notice-info" hidden>
    <h2>We are optimizing your video</h2>
    <div id="transcoding-progress" aria-live="polite">
        <p>We are optimizing your video for the web. This may take some time, so please fill out the media details.</p>
        <progress>loading</progress>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    require(['jquery'], function($) {
        var checkStatus = function() {
            \$.get('" . UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json', function(data) {
                if (data.transcoding_is_complete && data.transcoding_is_error) {
                    var \$message = $('#transcoding-progress');
                    \$message.html('<p>There was an error preparing your video. <a href=\"" . UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "\">Click here for details</a></p>');
                    \$message.closest('.dcf-notice').removeClass('dcf-notice-info').addClass('dcf-notice-warning');
                } else if (data.transcoding_is_complete) {
                    var \$message = $('#transcoding-progress');
                    \$message.html('<p>We have finished preparing your video. <a href=\"'+window.location+'\">Reload this page</a></p>');
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
