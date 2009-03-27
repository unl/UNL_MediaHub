<div id="feedlist">
    <h1>My Feeds</h1>
    <p>Select a channel to add your media to, or create a new channel.</p>
    <?php
    if (count($this->items)) {
        echo '<ul>';
        foreach ($this->items as $feed) {
            echo '<li><a href="'.htmlentities(UNL_MediaYak_Manager::getURL($feed)).'">'.$feed->title.'</a> <a href="'.UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml')).'" class="feed-icon"></a></li>';
        }
        echo '</ul>';
    } else {
        echo '
        <p>
            Sorry, you have no channels/feeds.
            <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Would you like to create one?</a>
        </p>';
    }
    echo '
        <p class="action">
            <a class="add_feed" href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Add channel</a>
        </p>';
    ?>
</div>