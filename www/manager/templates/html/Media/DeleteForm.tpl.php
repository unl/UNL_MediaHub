<form action="<?php echo UNL_MediaHub_Manager::getURL() ?>" method="post" class="confirm-delete">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="media_id" value="<?php echo $context->id; ?>" />
    <input type="submit" class="delete" value="Delete">
</form>
