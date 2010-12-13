<form action="" method="post" class="zenform cool" style="margin-top:-6px;">
    <fieldset id="addhead" class="daddhead_class">
        <ol>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input name="submit" value="Delete User" type="submit" /></div></li>
        </ol>
        <div style="display: none;">
            <input name="uid" type="hidden" value="<?php echo $context->uid; ?>" />
            <input name="delete" type="hidden" value="delete" />
            <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
            <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
        </div>
    </fieldset>
</form>