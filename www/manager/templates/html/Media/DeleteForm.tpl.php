<form id="deleteForm" action="<?php echo UNL_MediaHub_Manager::getURL() ?>" method="post" class="confirm-delete">
    <input type="hidden" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="media_id" value="<?php echo (int)$context->id; ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
    <button type="submit" class="delete dcf-btn dcf-btn-primary">Delete</button>
</form>
