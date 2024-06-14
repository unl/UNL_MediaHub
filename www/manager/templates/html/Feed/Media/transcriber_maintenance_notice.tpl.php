<div id="auto-captioning-maintenance" class="dcf-d-none">
    <div id="auto-captioning-maintenance-notice" class="dcf-notice dcf-notice-info" data-no-close-button="true" hidden>
        <h2>Auto Captions Maintenance Notice</h2>
        <div>
            We will be preforming maintenance on
            the auto captioning service on <span id="auto-captioning-maintenance-date-range"></span>. <br>
            Captions may not be generated at that
            time but they will be generated once
            the maintenance has finished.
        </div>
    </div>
</div>

<script>
    const api_status_url = '<?php echo UNL_MediaHub_TranscriptionManager::getURL(); ?>';
    const auto_captioning_maintenance = document.getElementById('auto-captioning-maintenance');
    const auto_captioning_maintenance_notice = document.getElementById('auto-captioning-maintenance-notice');

    async function check_captioning_status() {
        const auto_captioning_maintenance_date_range = document.getElementById('auto-captioning-maintenance-date-range');

        try {
            const api_status_response = await fetch(api_status_url);
            if (!api_status_response.ok) {
                console.error('Failed to get data from ' + api_status_url);
                return;
            }
            const parsed_data = await api_status_response.json();

            if ( !('status' in parsed_data) || parsed_data['status'] !== 'OK') {
                console.error('Bad result from ' + api_status_url);
                return;
            }

            if ( !('data' in parsed_data) || !('maintenance_date_range' in parsed_data['data']) ) {
                console.error('Bad data from ' + api_status_url);
                return;
            }

            auto_captioning_maintenance.classList.remove('dcf-d-none');
            auto_captioning_maintenance_date_range.innerText = parsed_data['data']['maintenance_date_range'];
        } catch (err) {
            console.error(err);
        }
    }

    window.addEventListener('inlineJSReady', function() {
        let timer = setInterval(() => {
            if (auto_captioning_maintenance_notice.classList.contains('dcf-notice-initialized')) {
                clearInterval(timer);
                check_captioning_status();
            }
        }, 200);
    }, false);
</script>
