<?php
if (isset($context->label) && !empty($context->label)) {
    UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media | '.$context->label);
    UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media Hub</a></li> <li>'.$context->label.'</li></ul>');
    echo '<h3>'.$context->label.'</h3>';
}

if (count($context->items)) {

    if ($parent->context instanceof UNL_MediaYak_FeedAndMedia) {
        // Use the feed url as the base for pagination links
        $url = UNL_MediaYak_Controller::getURL(
                    $parent->context->feed,
                        array_intersect_key(array_merge($context->options, array('page'=>'{%page_number}')), array('page'=>0, 'limit'=>0, 'order'=>0, 'orderby'=>0))
                );
    } elseif ($parent->context->options['view'] == 'search') {
        //blah
        $url = UNL_MediaYak_Controller::addURLParams($context->getURL(), array('page'=>'{%page_number}'));
    } else {
        $url = UNL_MediaYak_Controller::getURL(null, array_merge($context->options, array('page'=>'{%page_number}')));
    }

    $pager_layout = new UNL_MediaYak_List_PagerLayout($context->pager,
        new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
        $url);
    $pager_links = $pager_layout->display(null, true);
    
	$addMediaURL = UNL_MediaYak_Manager::getURL().'?view=addmedia';
	if (isset($_GET['id'])) {
	    $addMediaURL .= '&amp;feed_id='.$_GET['id'];
	}
	$userCanEdit = false;
	if (UNL_MediaYak_Controller::isLoggedIn()
        && $parent->context instanceof UNL_MediaYak_FeedAndMedia
        && $parent->context->feed->userHasPermission(UNL_MediaYak_Controller::getUser(),
            UNL_MediaYak_Permission::getByID(UNL_MediaYak_Permission::USER_CAN_INSERT))) {
        $userCanEdit = true;
    }
?>
    <div class="group" style="margin-top:20px;">
    	<h3>All Media</h3>
    	<?php
	    $addMediaURL = UNL_MediaYak_Manager::getURL().'?view=addmedia&amp;feed_id='.$parent->context->feed->id;
	    if (UNL_MediaYak_Controller::isLoggedIn()
	        && $parent->context->feed->userHasPermission(UNL_MediaYak_Controller::getUser(),
	            UNL_MediaYak_Permission::getByID(UNL_MediaYak_Permission::USER_CAN_INSERT))) {
	        echo '<a href="'.$addMediaURL.'" title="Add media to this feed" class="add_media" >Add media</a>';
	    }
	    
	    ?>
    </div>
        <ul class="medialist">
    
        <?php
        foreach ($context->items as $media) { ?>
            <li>
                <a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><img class="thumbnail" src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.urlencode($media->url); ?>" alt="Thumbnail preview for <?php echo $media->title; ?>" /></a>
                <?php if ($userCanEdit) { ?>
	            <div class="actions">
		            <a href="<?php echo $addMediaURL; ?>&amp;id=<?php echo $media->id; ?>">Edit</a>
		            <?php
		            echo $savvy->render($media, 'manager/templates/Media/DeleteForm.tpl.php');
		            ?>
	            </div>
	            <?php }?>
                <div class="metaInfo">
                <h4><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a></h4>
                <?php
                $element = $media->datecreated;
                    echo '<h6 class="subhead">Added on '.date("F j, Y, g:i a", strtotime($element)).'</h6>';
                
                $summary = $media->description;
                if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'summary')) {
                    $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
                }
                $summary = strip_tags($summary, '<a><img>');
                $summary = str_replace('Related Links', '', $summary);
                ?>
                <p><?php echo $summary; ?></p>
                </div>
            </li>
        <?php  
        } ?>
        </ul>
        <em>Displaying <?php echo $context->first; ?> through <?php echo $context->last; ?> out of <?php echo $context->total; ?></em>
        <?php echo $pager_links; ?>
<?php
} else {
    echo '<p>Sorry, no media could be found</p>';
}
?>