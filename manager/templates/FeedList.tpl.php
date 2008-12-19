<?php
if (count($this->items)) {
    echo '<h1>My Channels</h1><ul>';
    foreach ($this->items as $feed) {
        echo '<li><a href="'.htmlentities(UNL_MediaYak_Manager::getURL($feed)).'">'.$feed->title.'</a></li>';
    }
    echo '</ul>';
} else {
    echo '
    <p>
        Sorry, you have no channels/feeds.
        <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Would you like to create one?</a>
    </p>';
}
echo '<p>
        <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Create a new channel</a>
    </p>';