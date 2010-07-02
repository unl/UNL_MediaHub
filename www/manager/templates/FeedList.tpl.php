<div id="feedlist">
    <h1>Your Channels</h1>
    <p>Select a channel to add your media to, or create a new channel.</p>
    <?php
    if (count($context->items)) {
        echo 'You have '.$context->total. ' channel(s):';
        echo '<ul>';
        foreach ($context->items as $feed) {
            echo '<li><a href="'.htmlspecialchars(UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml'))).'" title="RSS feed for this channel" class="feed-icon"></a> <a href="'.htmlspecialchars(UNL_MediaYak_Manager::getURL($feed), ENT_QUOTES).'" title="Edit this channel" class="edit_channel"></a> <a href="'.htmlspecialchars(UNL_MediaYak_Manager::getURL($feed), ENT_QUOTES).'">'.$feed->title.'</a> </li>'.PHP_EOL;
        }
        echo '</ul>';
    } else {
        echo '
        <p>
            Sorry, you have no channels.
            <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Would you like to create one?</a>
        </p>';
    }
    echo '
        <p class="action">
            <a class="add_feed" href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Create a channel</a>
        </p>';
    ?>
</div>