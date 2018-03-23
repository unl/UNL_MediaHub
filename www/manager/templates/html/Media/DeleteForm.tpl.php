<form id="deleteForm" action="<?php echo UNL_MediaHub_Manager::getURL() ?>" method="post" class="confirm-delete">
    <input type="hidden" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="media_id" value="<?php echo (int)$context->id; ?>" />
    <button type="submit" class="delete wdn-button wdn-button-brand">Delete</button>
</form>
