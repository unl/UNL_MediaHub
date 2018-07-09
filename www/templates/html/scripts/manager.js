require(['jquery'], function($){
    $(document).ready(function() {
        $('.confirm-delete').submit(function () {
            if (confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) {
                return true;
            }

            return false;
        });
        
        $('#delete-media').click(function(){
            $('#deleteForm').submit();
            return false; //prevent default action
        });
        
        var caption_order_submit = 0;
        $('#caption_order').submit(function() {
            if (caption_order_submit > 0) {
                //prevent double submission
                return false;
            }
            
            caption_order_submit++;
            var $button = $('#caption_submit_button');
            $button.attr("disabled", true);
            $button.val("Please wait...");
        });
    });
});
