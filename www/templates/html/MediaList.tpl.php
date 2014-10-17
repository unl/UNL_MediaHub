<?php
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | Media | '.$context->label);
    $controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    echo '<h1>'.$context->label.'</h1>';
}

if (count($context->items)) {

    if ($parent->context instanceof UNL_MediaHub_FeedAndMedia) {
        // Use the feed url as the base for pagination links
        $url = UNL_MediaHub_Controller::getURL(
                    $parent->context->feed,
                        array_intersect_key(array_merge($context->options, array('page'=>'{%page_number}')), array('page'=>0, 'limit'=>0, 'order'=>0, 'orderby'=>0))
                );
    } elseif (isset($parent->context->options['model']) && $parent->context->options['model'] == 'UNL_MediaHub_MediaList') {
        //blah
        $url = UNL_MediaHub_Controller::addURLParams($context->getURL(), array('page'=>'{%page_number}'));
    } else {
        $url = UNL_MediaHub_Controller::getURL(null, array_merge($context->options, array('page'=>'{%page_number}')));
    }

    $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
        new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
        htmlentities($url));
    $pager_links = $pager_layout->display(null, true);
    
	$addMediaURL = UNL_MediaHub_Manager::getURL().'?view=addmedia';
	if (isset($_GET['id'])) {
	    $addMediaURL .= '&amp;feed_id='.$_GET['id'];
	}
	$userCanEdit = false;

	if ($controller->isLoggedIn()
        && $parent->context instanceof UNL_MediaHub_FeedAndMedia
        && $parent->context->feed->userHasPermission(UNL_MediaHub_Controller::getUser(),
            UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_INSERT))) {
        $userCanEdit = true;
    }
?>
    <div class="group">
    	<?php
    	if ($parent->context instanceof UNL_MediaHub_FeedAndMedia) {
		    $addMediaURL = UNL_MediaHub_Manager::getURL().'?view=addmedia&amp;feed_id='.$parent->context->feed->id;
		    if (UNL_MediaHub_Controller::isLoggedIn()
		        && $parent->context->feed->userHasPermission(UNL_MediaHub_Controller::getUser(),
		            UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_INSERT))) {
		        echo '<a href="'.$addMediaURL.'" title="Add media to this feed" class="add_media" >Add media</a>';
		    }
    	}
	    ?>
    </div>

    <div class="bp2-wdn-grid-set-thirds">
        <?php
        foreach ($context->items as $media):
            ?>
            <div class="wdn-col">
                <?php echo $savvy->render($media, 'templates/html/Media/teaser.tpl.php'); ?>
            </div>
        <?php
        endforeach;
        ?>
    </div>
        
        <em>Displaying <?php echo $context->first; ?> through <?php echo $context->last; ?> out of <?php echo $context->total; ?></em>
        <?php echo $pager_links; ?>
<?php
} else {
    echo "<div style='width:940px'><p>Sorry, no media could be found</p></div>";
}
?>