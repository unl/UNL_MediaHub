<div id="feedlist">
    <h1>Available Channels</h1>
    <p>Select a channel to view.</p>
    <?php
    if (count($this->items)) {
        echo '<ul>';
        foreach ($this->items as $feed) {
            echo '<li><a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed)).'">'.$feed->title.'</a> <a href="'.UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml')).'" class="feed-icon"></a>
            <div class="description">'.$feed->description.'</div>
            </li>';
        }
        echo '</ul>';
    } else {
        echo '
        <p>
            Sorry, I could not find any channels.
        </p>';
    }
    ?>
</div>