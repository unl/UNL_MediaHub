require(['jquery'], function($){
    $(document).ready(function() {
        $('.confirm-delete').submit(function () {
            if (confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) {
                return true;
            }

            return false;
        });
    });
});
