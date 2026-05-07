window.mediahub = window.mediahub ?? {};
console.log(window.mediahub);

async function check_status_transcribe() {
    const response = await fetch(window.mediahub.transcribe_status_api);

    if (!response.ok) {
        return;
    }

    const data = await response.json();

    if (
        'transcription_queue_position' in data && parseInt(data.transcription_queue_position) >= 0 && 
        'transcription_queue_length' in data && parseInt(data.transcription_queue_length) >= 0
    ) {
        const queue_position_element = document.querySelector('.transcription_queue_position');
        queue_position_element.innerText = data.transcription_queue_position;

        const queue_length_element = document.querySelector('.transcription_queue_length');
        queue_length_element.innerText = data.transcription_queue_length;

        const queue_data_element = document.querySelector('.transcription_queue_data');
        queue_data_element.classList.remove('dcf-d-none!');
    } else {
        const queue_data_element = document.querySelector('.transcription_queue_data');
        queue_data_element.classList.add('dcf-d-none!');
    }

    const message = document.getElementById('ai-caption-progress');
    const notice = message.closest('.dcf-notice');

    if (data.transcoding_is_complete && data.transcoding_is_error) {
        message.innerHTML = `<p>There was an error preparing your video. <a href="${window.location}">Reload this page</a></p>`;
        notice.classList.remove('dcf-notice-info');
        notice.classList.remove('dcf-notice-warning');
    } else if (data.transcoding_is_complete) {
        message.innerHTML = `<p>We have finished preparing your video. <a href="${window.location}">Reload this page</a></p>`;
        notice.classList.remove('dcf-notice-info');
        notice.classList.remove('dcf-notice-success');
    } else {
        setTimeout(check_status_transcribe, 10000);
    }
}
check_status_transcribe();
