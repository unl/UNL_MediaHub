<div class="dcf-notice dcf-notice-info" hidden>
    <h2>We are captioning your media</h2>
    <div id="ai-caption-progress" aria-live="polite">
        <p class="dcf-mb-1">
            We are captioning your media. This may take some time,
            so please fill out the media details while you wait.
        </p>
        <p class="transcription_queue_data dcf-d-none dcf-mb-3">Queue Position: <span class="transcription_queue_position">-1</span> of <span class="transcription_queue_length">-1</span></p>
        <progress>loading</progress>
    </div>
</div>

<?php
$page->addScriptDeclaration("
    require(['jquery'], function($) {
        var checkStatus = function() {
            \$.get('" . UNL_MediaHub_Manager::getURL() . "?view=addmedia&id=" . $context->media->id . "&format=json', function(data) {
                if (
                    'transcription_queue_position' in data && parseInt(data.transcription_queue_position) >= 0 && 
                    'transcription_queue_length' in data && parseInt(data.transcription_queue_length) >= 0
                ) {
                    const queue_position_element = document.querySelector('.transcription_queue_position');
                    queue_position_element.innerText = data.transcription_queue_position;

                    const queue_length_element = document.querySelector('.transcription_queue_length');
                    queue_length_element.innerText = data.transcription_queue_length;

                    const queue_data_element = document.querySelector('.transcription_queue_data');
                    queue_data_element.classList.remove('dcf-d-none');
                } else {
                    const queue_data_element = document.querySelector('.transcription_queue_data');
                    queue_data_element.classList.add('dcf-d-none');
                }

                if (data.transcription_is_complete && data.transcription_is_error) {
                    var \$message = $('#ai-caption-progress');
                    \$message.html('<p>There was an error processing your captions. <a href=\"'+window.location+'\">Reload this page</a></p>');
                    \$message.closest('.dcf-notice').removeClass('dcf-notice-info').addClass('dcf-notice-warning');
                } else if (data.transcription_is_complete) {
                    var \$message = $('#ai-caption-progress');
                    \$message.html('<p>We have finished preparing your captions. <a href=\"'+window.location+'\">Reload this page</a></p>');
                    \$message.closest('.dcf-notice').removeClass('dcf-notice-info').addClass('dcf-notice-success');
                } else {
                    //Try again in 10 seconds
                    setTimeout(checkStatus, 10000);
                }
            });
        };

        checkStatus();
    });");
