document.addEventListener('DOMContentLoaded', function () {

    // Confirm before deleting
    document.querySelectorAll('.confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) {
                event.preventDefault();
            }
        });
    });

    // Delete link submits the delete form
    var deleteMedia = document.getElementById('delete-media');
    if (deleteMedia) {
        deleteMedia.addEventListener('click', function (event) {
            event.preventDefault(); // prevent default action
            document.getElementById('deleteForm').requestSubmit();
        });
    }

    // Prevent double submission of the caption order form
    var captionOrderSubmit = 0;
    var captionOrder = document.getElementById('caption_order');
    if (captionOrder) {
        captionOrder.addEventListener('submit', function (event) {
            if (captionOrderSubmit > 0) {
                event.preventDefault(); // prevent double submission
                return;
            }

            captionOrderSubmit++;
            var button = document.getElementById('caption_submit_button');
            button.disabled = true;
            button.value = 'Please wait...';
        });
    }
});