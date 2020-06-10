<div class="wdn_notice">
    <div class="message">
        <h2 class="title">We are optimizing your video</h2>
        <div id="transcoding-progress" aria-live="polite">
            <p>
                We are optimizing your video for the web. This may take some time, so please fill out the media details or submit a caption order while you wait. </p>
            <progress>loading</progress>
        </div>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    require(['jquery'], function($) {
        var checkStatus = function() {
            \$.get('" . UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json', function(data) {
                if (data.transcoding_is_complete) {
                    var \$message = $('#transcoding-progress');
                    \$message.html('<p>We have finished preparing your video. <a href=\"'+window.location+'\">Reload this page</a></p>');
                    \$message.closest('.wdn_notice').addClass('affirm');
                } else {
                    //Try again in 10 seconds
                    setTimeout(checkStatus, 10000);
                }
            });
        };

        checkStatus();
    });");
?>