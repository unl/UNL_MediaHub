const megabyte = 1048576;
const form_url = baseurl + '?view=upload&format=json';

// Set up container
const mh_upload_media_container = document.getElementById('mh_upload_media_container');
mh_upload_media_container.classList.add('dcf-relative', 'dcf-h-fit-content');

// Set up input element
const input_element = document.createElement('input');
input_element.setAttribute('id', 'file_' + window.crypto.randomUUID());
input_element.setAttribute('type', 'file');
input_element.setAttribute('accept', '.mp4,.mov,.mp3');
input_element.classList.add('dcf-absolute', 'dcf-top-0', 'dcf-left-0', 'dcf-right-0', 'dcf-bottom-0');
input_element.style.fontSize = 0;
input_element.style.zIndex = 0;

// Set up label element
const label_element = document.createElement('label');
label_element.setAttribute('for', input_element.getAttribute('id'));
label_element.classList.add('dcf-sr-only');
label_element.innerText = 'Browse for media';

// Set up error element
const error_element = document.createElement('p');
error_element.classList.add('unl-bg-scarlet', 'unl-cream', 'dcf-rounded', 'dcf-p-4', 'dcf-mt-4', 'dcf-txt-sm', 'dcf-bold', 'dcf-d-none');
error_element.innerText = 'There was an error uploading your media. Please try refreshing the page and trying again or reach out to an administrator if the issue persists.';

// Add new elements to media container
mh_upload_media_container.append(input_element);
mh_upload_media_container.append(error_element);
mh_upload_media_container.append(label_element);

// Make sure media upload and fileList are in front of input element
const mh_upload_media = document.getElementById('mh_upload_media');
mh_upload_media.classList.add('dcf-relative');
mh_upload_media.style.zIndex = 1;

const fileList = document.getElementById('filelist');
fileList.style.zIndex = 1;



// Event Listeners
mh_upload_media.addEventListener('click', () => {
    input_element.click();

    input_element.addEventListener('change', () => {
        const file = input_element.files[0];

        uploadInChunks(file);
    });
});

// These are for when you drag and drop a media file
mh_upload_media.addEventListener("dragover", function(event) {
    event.preventDefault();
    mh_upload_media.classList.add('mh-focus-outline');
}, false);

mh_upload_media.addEventListener("dragleave", function(event) {
    event.preventDefault();
    mh_upload_media.classList.remove('mh-focus-outline');
}, false);

mh_upload_media.addEventListener("drop", function(event) {
    // Cancel default actions
    event.preventDefault();
    let file = event.dataTransfer.files[0];
    uploadInChunks(file);
}, false);



/**
 * Uploads the files in chunks to the server
 * @param { File } file 
 * @returns { void }
 */
async function uploadInChunks(file) {
    if (file.size > 10240 * megabyte) {
        alert('File uploaded is too large');
        return;
    }

    statusUploading(file.name, humanReadableBytes(file.size));
    const CHUNK_SIZE = 5 * megabyte;
    const csrf_name = document.querySelector('input[name="csrf_name"]').value;
    const csrf_value = document.querySelector('input[name="csrf_value"]').value;
    const randomID = window.crypto.randomUUID();
    const extension = file.name.split('.').pop();

    let chunks = [];
    const tmp_chunks_length = Math.ceil(file.size / CHUNK_SIZE);

    setProgressText('Processing');
    setProgressTotal(tmp_chunks_length);

    // Split the file into chunks and generate a hash checksum of each chunk
    let start = 0;
    let end = CHUNK_SIZE;
    let chunk_count = 0;
    while (start < file.size) {
        let chunkData = file.slice(start, end);

        const chunkArrayBuffer = await chunkData.arrayBuffer();
        const hashBuffer = await crypto.subtle.digest('SHA-1', chunkArrayBuffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const chunkHash = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');

        chunks.push({
            index: chunk_count,
            chunkData: chunkData,
            chunkHash: chunkHash,
        });

        chunk_count++;
        start = end;
        end = start + CHUNK_SIZE;
        incrementProgress();
    }

    setProgressText('Uploading');
    setProgressTotal(chunks.length);
    try {
        // If we ever fail to upload a chunk after the retries this will allow us to abort
        // all the rest of the requests
        const abortSignal = new AbortController();

        // Probably not a good idea to send all the requests at the same time
        const batchSize = 50;
        for (let batch = 0; batch < chunks.length; batch += batchSize) {
            const currentBatch = chunks.slice(batch, batch + batchSize);

            // Send all the chunks in parallel
            await Promise.all(currentBatch.map((single_chunk, index) => {
                let formData = new FormData();
                formData.append('file', single_chunk.chunkData);
                formData.append('name', file.name);
                formData.append('index', single_chunk.index);
                formData.append('randomID', randomID);
                formData.append('extension', extension);
                formData.append('hash', single_chunk.chunkHash);
                formData.append('chunkSize', CHUNK_SIZE);
                formData.append('__unlmy_posttarget', 'upload_media');
                formData.append('csrf_name', csrf_name);
                formData.append('csrf_value', csrf_value);
    
                return uploadSingleChunk(formData, abortSignal);
            }));

        }
    } catch (err) {
        console.error(err);
        statusFailed();
        return;
    }

    status_finalizing();

    let formData = new FormData();
    formData.append('name', file.name);
    formData.append('randomID', randomID);
    formData.append('extension', extension);
    formData.append('isFinal', true);
    formData.append('__unlmy_posttarget', 'upload_media');
    formData.append('csrf_name', csrf_name);
    formData.append('csrf_value', csrf_value);

    // Sends the `isFinal` request which should squish the chunks back into the original file
    // If it works out good then it should return the url of the uploaded media file
    try {
        const response = await fetch(form_url, {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            console.error('Bad status on final request');
            statusFailed();
        }
        const jsonData = await response.json();
        statusComplete(jsonData.url);

    } catch (err) {
        console.error(err);
        statusFailed();
    }
}

/**
 * Uploads a single chunk to the server
 * If it fails it will retry X number of times
 * @param { FormData } formData
 * @param { AbortController } abortSignal
 * @returns { Promise<String> }
 */
function uploadSingleChunk(formData, abortSignal) {
    const max_retries = 10;
    const sleep_time_ms = 200;
    return new Promise(async (resolve, reject) => {
        for (let current_retry = 0; current_retry < max_retries; current_retry++) {
            if (abortSignal.signal.aborted) {
                reject('Failed: Aborted');
                return;
            }
            try {
                const response = await fetch(form_url, {
                    method: 'POST',
                    signal: abortSignal.signal,
                    body: formData
                });
                if (!response.ok) {
                    // Linearly extend the sleep time as the retries keep failing
                    await sleep(sleep_time_ms * (current_retry + 1));
                    continue;
                }
                incrementProgress();
                resolve('Success');
                return;
            } catch (err) {
                abortSignal.abort();
                reject('Failed: Error uploading chunk');
                return;
            }
        }
        abortSignal.abort();
        reject('Failed: Too many retries to upload chunk');
    });
}



/**
 * Converts the number of bytes to a human readable format
 * @param { Number } size 
 * @param { String } unit 
 * @returns { String } HTML of the human readable bytes
 */
function humanReadableBytes(size, unit = "") {
    const format = n => {
        const str = n.toFixed(2);
        return str.endsWith(".00") ? str.slice(0, -3) : str;
    };

    if ((!unit && size >= 1 << 30) || unit === "GB") {
        return format(size / (1 << 30)) + "<abbr title='Gigabytes'>GB</abbr>";
    }
    if ((!unit && size >= 1 << 20) || unit === "MB") {
        return format(size / (1 << 20)) + "<abbr title='Megabytes'>MB</abbr>";
    }
    if ((!unit && size >= 1 << 10) || unit === "KB") {
        return format(size / (1 << 10)) + "<abbr title='Kilobytes'>KB</abbr>";
    }
    return format(size) + " bytes";
}

/**
 * Sleeps for X number of milliseconds
 * @param { Number } ms 
 * @returns { Promise<void> }
 */
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}



/**
 * Sets up the upload form to display the uploading state
 * @param { String } fileName 
 * @param { Number } fileSize 
 */
function statusUploading(fileName, fileSize) {
    const submit_button = document.querySelector('input[type=submit]');
    submit_button.dataset.text = submit_button.getAttribute('value');
    submit_button.setAttribute('disabled', 'disabled');
    submit_button.setAttribute('value', 'Upload in Progress...');

    fileList.innerHTML = '<div class="dcf-w-max-100% dcf-word-wrap" style="hyphens:none;">' + fileName + '<br> (' + fileSize + ') <b></b></div>';
    fileList.getElementsByTagName('b')[0].innerHTML = '<span>Loading video...</span>';

    mh_upload_media.classList.add('dcf-d-none');
    fileList.classList.remove('dcf-d-none');

    input_element.classList.add('dcf-d-none');
    label_element.classList.add('dcf-d-none');
}

/**
 * Sets up the form to display the failed to upload state
 */
function statusFailed() {
    error_element.classList.remove('dcf-d-none');
}

function status_finalizing() {
    fileList.getElementsByTagName('b')[0].innerHTML = '<span>Finalizing Upload...</span><br><span>This may take some time.</span>';
}

/**
 * Sets up the form to display the upload complete state
 * @param { String } response_url 
 */
function statusComplete(response_url) {
    const submit_button = document.querySelector('input[type=submit]');
    submit_button.removeAttribute('disabled');
    submit_button.setAttribute('value', submit_button.dataset.text);

    const media_url = document.getElementById('media_url');
    media_url.setAttribute('value', response_url);

    const publish = document.getElementById('publish');
    if (publish !== null) {
        publish.removeAttribute('disabled');
    }

    fileList.getElementsByTagName('b')[0].innerHTML = '<span>Complete!</span>';
}

// Set up progress bar variables
let progressText = '';
let progressTotal = 0;
let progressAmount = 0;

function setProgressText(newProgressText) {
    progressText = newProgressText;
}

/**
 * Sets the total amount of progress that can be made
 * @param { Number } newProgressTotal 
 */
function setProgressTotal(newProgressTotal) {
    progressTotal = newProgressTotal;
    progressAmount = 0;
}

/**
 * Increments the progress and displays the new percent
 */
function incrementProgress() {
    progressAmount++;

    const progressPercent = ((progressAmount / progressTotal) * 100).toFixed();
    fileList.getElementsByTagName('b')[0].innerHTML = '<span>' + progressText + ': ' + progressPercent + "%</span>";
}
