<div id="feedlist">
    <h1>Available Channels</h1>
    <p>Select a channel to view.</p>
    <div class="clear"></div>
    <?php
    if (count($context->items)) {
        $pager_layout = new UNL_MediaYak_List_PagerLayout($context->pager,
            new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
            UNL_MediaYak_Controller::getURL(null, array_merge($context->options, array('page'=>'{%page_number}'))));
        $pager_links = $pager_layout->display(null, true);
        echo '<ul>';
        foreach ($context->items as $feed) {
        	echo '<li>
        	<a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'"><img src="../manager/templates/css/images/iconTV.png" alt="'.htmlentities($feed->title).' image" width="150" height="84" /></a>
        	<div class="aboutFeed">
        	<h3><a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'">'.htmlentities($feed->title).'</a> </h3>
            <h6 class="subhead">This channel was created by <a href="http://peoplefinder.unl.edu/?uid='.$feed->uidcreated.'" class="wdnPeoplefinder" title="'.UNL_Services_Peoplefinder::getFullName($feed->uidcreated).'\'s Peoplefinder Record">'.UNL_Services_Peoplefinder::getFullName($feed->uidcreated).'</a> on '.date("F j, Y, g:i a", strtotime($feed->datecreated)).'.</h6>
            <p>'.htmlentities($feed->description).'</p>
            <ul>
                <li><a href="'.UNL_MediaYak_Controller::getURL($feed, array('format'=>'xml')).'" class="feed-icon"></a></li>
            </ul>
            </div>
            <div class="clear"></div>
            </li>';
        }
        echo '</ul>';
        ?>
        </div>
        <em>Displaying <?php echo $context->first; ?> through <?php echo $context->last; ?> out of <?php echo $context->total; ?></em>
        <?php echo $pager_links; ?>
<?php 
    } else {
        echo '
        <p>
            Sorry, I could not find any channels.
        </p>';
    }
    ?>
