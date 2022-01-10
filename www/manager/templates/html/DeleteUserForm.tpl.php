<form class="dcf-form" action="" method="post">
    <input name="uid" type="hidden" value="<?php echo UNL_MediaHub::escape($context->uid); ?>" />
    <input name="delete" type="hidden" value="delete" />
    <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
    <button class="dcf-btn dcf-btn-primary" type="submit" value="Remove" title="Remove">X</button>
</form>