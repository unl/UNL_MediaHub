<?php
// Need to add <boxee:image>'..'</boxee:image>
if (count($context->items)) {
    foreach ($context->items as $feed) {
        echo '
    <item>
        <title>'.htmlspecialchars($feed->title).'</title>
        <description>'.htmlspecialchars($feed->description).'</description>
        <link>'.str_replace('http://', 'rss://', UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml'))).'</link>
    </item>';
    }
} else {
    echo '<!-- Sorry, I could not find any channels. -->';
}
?>