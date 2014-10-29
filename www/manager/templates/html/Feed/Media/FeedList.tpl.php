<?php
foreach ($context->items as $item=>$feed) {
    $checked = '';
    if ((isset($context->media) && ($feed->hasMedia($context->media))
        || (isset($_GET['feed_id']) && $_GET['feed_id'] == $feed->id))) {
        $checked = 'checked="checked"';
    }
    echo '<li><input id="feed_id['.$feed->id.']" name="feed_id['.$feed->id.']" type="checkbox" '.$checked.' /> <label for="feed_id['.$feed->id.']">'.$feed->title.'</label></li>';
}
