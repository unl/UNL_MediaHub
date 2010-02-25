<?php
foreach ($this->items as $item=>$feed) {
    $checked = '';
    if ((isset($this->media) && ($feed->hasMedia($this->media))
        || (isset($_GET['feed_id']) && $_GET['feed_id'] == $feed->id))) {
        $checked = 'checked="checked"';
    }
    echo '<li><label for="feed_id['.$feed->id.']" class="element">'.$feed->title.'</label><div class="element"><input id="feed_id['.$feed->id.']" name="feed_id['.$feed->id.']" type="checkbox" '.$checked.' /></div></li>';
}
?>