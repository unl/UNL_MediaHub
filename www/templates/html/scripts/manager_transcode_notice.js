window.mediahub = window.mediahub ?? {};
console.log(window.mediahub);

async function check_status_transcode() {
    const response = await fetch(window.mediahub.transcoding_status_api);

    if (!response.ok) {
        return;
    }

    const data = await response.json();
    const message = document.getElementById('transcoding-progress');
    const notice = message.closest('.dcf-notice');

    if (data.transcoding_is_complete && data.transcoding_is_error) {
        message.innerHTML = `<p>There was an error preparing your video. <a href="${window.mediahub.add_media_url}">Click here for details</a></p>`;
        notice.classList.remove('dcf-notice-info');
        notice.classList.remove('dcf-notice-warning');
    } else if (data.transcoding_is_complete) {
        message.innerHTML = `<p>We have finished preparing your video. <a href="${window.location}">Reload this page</a></p>`;
        notice.classList.remove('dcf-notice-info');
        notice.classList.remove('dcf-notice-success');
    } else {
        setTimeout(check_status_transcode, 10000);
    }
}
check_status_transcode();
