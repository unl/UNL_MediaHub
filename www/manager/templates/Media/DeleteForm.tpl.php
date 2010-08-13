<form action="?view=feed&amp;id=<?php echo $parent->parent->context->options['id']; ?>" method="post" id="deletemedia_<?php echo $context->id; ?>" style="width:120px;">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="feed_id" value="<?php echo $parent->parent->context->options['id']; ?>" />
    <input type="hidden" name="media_id" value="<?php echo $context->id; ?>" />
    <a href="#" onclick="if (confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) document.getElementById('deletemedia_<?php echo $context->id; ?>').submit();">Delete</a>
</form>