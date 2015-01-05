<form action="" method="post">
    <input name="uid" type="hidden" value="<?php echo $context->uid; ?>" />
    <input name="delete" type="hidden" value="delete" />
    <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    <button type="submit" value="Remove" title="Remove">X</button>
</form>