<?php 
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | MediaHub | Your Media');
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">MediaHub</a></li> <li>Your Media</li></ul>');
?>
<div id="feedlist" class="two_col left">
    <h1 class="sec_header">Your Channels</h1>
    <h4>Channels are collections of your media. Use channels to organize specific shows (ex: Backyard Farmer).</h4>
    <?php
    if (count($context->items)) {
        $pager_layout = new UNL_MediaYak_List_PagerLayout($context->pager,
            new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                    UNL_MediaYak_Manager::getURL($context, array_merge($context->options, array('page'=>'{%page_number}'))));
        $pager_links = $pager_layout->display(null, true);
        echo '<h6 class="list_header">You have '.$context->total. ' channel(s):</h6>';
        echo '<ul>';
        foreach ($context->items as $feed) {
            echo '<li><a href="'.htmlspecialchars(UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml'))).'" title="RSS feed for this channel" class="feed-icon"></a> <a href="'.UNL_MediaYak_Manager::getURL().'?view=feedmetadata&amp;id='.$feed->id.'" title="Edit this channel" class="edit solo"></a> <a href="'.htmlspecialchars(UNL_MediaYak_Manager::getURL($feed), ENT_QUOTES).'">'.$feed->title.'</a> </li>'.PHP_EOL;
        }
        echo '</ul>';
        echo '<em>Displaying '.$context->first.' through '.$context->last.' out of '.$context->total.'</em>'.
        $pager_links;
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