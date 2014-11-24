<form action="<?php echo UNL_MediaHub_Manager::getURL() ?>" method="post" id="deletemedia_<?php echo $context->id; ?>">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="media_id" value="<?php echo $context->id; ?>" />
    <a href="#" class="delete" onclick="if (confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) document.getElementById('deletemedia_<?php echo $context->id; ?>').submit();">Delete</a>
</form>
