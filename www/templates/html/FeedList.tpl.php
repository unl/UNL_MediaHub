<?php
$label = 'Available Channels';
if (isset($context->label) && !empty($context->label)) {
    UNL_MediaYak_Controller::setReplacementData('title', 'UNL | MediaHub | '.$context->label);
    UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    $label = $context->label;
}
?>
<div id="feedlist">
    <h1><?php echo $label; ?></h1>
    <?php
    if (count($context->items)) {
        $pager_layout = new UNL_MediaYak_List_PagerLayout($context->pager,
            new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                    UNL_MediaYak_Controller::getURL($context, array_merge($context->options, array('page'=>'{%page_number}'))));
        $pager_links = $pager_layout->display(null, true);
        echo '<ul>';
        foreach ($context->items as $feed) {
            echo '<li>
            <a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'"><img src="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'/image" alt="'.htmlentities($feed->title).' image" /></a>
            <div class="aboutFeed">
            <h3><a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'">'.htmlentities($feed->title).'</a> </h3>
            '.$savvy->render($feed, 'Feed/Creator.tpl.php').'
            <p>'.htmlentities($feed->description).'</p>';
            //@TODO add a check if user is logged in and if has permissions to this feed to edit. If true, add edit/delete links here.
            echo '</div>
    		<div class="mediaSamples">
    			'.$savvy->render($feed->getMediaList(), 'CompactMediaList.tpl.php').'
    			<a href="'.htmlentities(UNL_MediaYak_Controller::getURL($feed), ENT_QUOTES).'" title="View all the media in this channel">See all media</a>
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
