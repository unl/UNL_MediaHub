<?php
// Need to add <boxee:image>'..'</boxee:image>
if (count($context->items)) {
    foreach ($context->items as $feed) {
        echo '
    <item>
        <title>'.htmlspecialchars($feed->title).'</title>
        <description>'.htmlspecialchars($feed->description).'</description>
        <link>'.str_replace('http://', 'rss://', UNL_MediaYak_Controller::getURL($feed)).'?format=xml</link>';
        if ($feed->hasImage()) {
            echo '<media:thumbnail url="'.UNL_MediaYak_Controller::getURL($feed).'/image"></media:thumbnail>'.PHP_EOL;
            echo '<boxee:image>'.UNL_MediaYak_Controller::getURL($feed).'/image</boxee:image>'.PHP_EOL;
        }
    echo '
    </item>';
    }
} else {
    echo '<!-- Sorry, I could not find any channels. -->';
}
?>