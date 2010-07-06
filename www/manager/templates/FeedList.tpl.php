<div id="feedlist">
    <h1>Your Channels</h1>
    <h4>Channels are collections of your media. Use channels to organize specific shows (ex: Backyard Farmer).</h4>
    <?php
    if (count($context->items)) {
        echo '<h6 class="list_header">You have '.$context->total. ' channel(s):</h6>';
        echo '<ul>';
        foreach ($context->items as $feed) {
            echo '<li><a href="'.htmlspecialchars(UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml'))).'" title="RSS feed for this channel" class="feed-icon"></a> <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata&amp;id='.$feed->id.'" title="Edit this channel" class="edit solo"></a> <a href="'.htmlspecialchars(UNL_MediaYak_Manager::getURL($feed), ENT_QUOTES).'">'.$feed->title.'</a> </li>'.PHP_EOL;
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
        <a class="action add_feed" href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata">Create a channel</a>';
    ?>
</div>