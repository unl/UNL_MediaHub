<?php
$action = '#';

if ($parent->parent->context instanceof UNL_MediaYak_FeedAndMedia) {
    $feed_id = (int)$parent->parent->context->feed->id;
    $action = '?view=feed&amp;id='.$feed_id;
} elseif ($parent->parent->context instanceof UNL_MediaYak_Manager) {
    $feed_id = (int)$parent->parent->context->options['id'];
    $action = '?view=feed&amp;id='.(int)$parent->parent->context->options['id'];
}
?>
<form action="<?php echo $action; ?>" method="post" id="deletemedia_<?php echo $context->id; ?>">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="delete_media" />
    <input type="hidden" name="feed_id" value="<?php echo $feed_id; ?>" />
    <input type="hidden" name="media_id" value="<?php echo $context->id; ?>" />
    <a href="#" class="delete" onclick="if (confirm('Are you sure? This will delete this media, and remove it from any associated channels.')) document.getElementById('deletemedia_<?php echo $context->id; ?>').submit();">Delete</a>
</form>