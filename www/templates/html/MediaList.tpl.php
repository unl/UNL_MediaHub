<?php
if (isset($context->label) && !empty($context->label)) {
    UNL_MediaHub_Controller::setReplacementData('title', 'UNL | Media | '.$context->label);
    UNL_MediaHub_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    echo '<h3>'.$context->label.'</h3>';
}

if (count($context->items)) {

    if ($parent->context instanceof UNL_MediaHub_FeedAndMedia) {
        // Use the feed url as the base for pagination links
        $url = UNL_MediaHub_Controller::getURL(
                    $parent->context->feed,
                        array_intersect_key(array_merge($context->options, array('page'=>'{%page_number}')), array('page'=>0, 'limit'=>0, 'order'=>0, 'orderby'=>0))
                );
    } elseif ($parent->context->options['model'] == 'UNL_MediaHub_MediaList') {
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
	if (UNL_MediaHub_Controller::isLoggedIn()
        && $parent->context instanceof UNL_MediaHub_FeedAndMedia
        && $parent->context->feed->userHasPermission(UNL_MediaHub_Controller::getUser(),
            UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_INSERT))) {
        $userCanEdit = true;
    }
?>
    <div class="group" style="margin-top:20px;">
    	<h3>All Media</h3>
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
        <ul class="medialist">
    
        <?php
        foreach ($context->items as $media) { ?>
            <li>
                <div class="wdn-grid-set">
                    <div class="wdn-col-one-fourth">
                        <a href="<?php echo UNL_MediaHub_Controller::getURL($media); ?>"><img class="thumbnail" src="<?php echo $media->getThumbnailURL(); ?>" alt="Thumbnail preview for <?php echo htmlspecialchars($media->title, ENT_QUOTES); ?>" /></a>
                        <?php if ($userCanEdit) { ?>
                            <div class="actions">
                                <a href="<?php echo $addMediaURL; ?>&amp;id=<?php echo $media->id; ?>">Edit</a>
                                <?php
                                echo $savvy->render($media, 'manager/templates/Media/DeleteForm.tpl.php');
                                ?>
                            </div>
                        <?php }?>
                    </div>
                    <div class="wdn-col-three-fourths metaInfo">
                        <div class="wdn-head">
                            <a href="<?php echo UNL_MediaHub_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a>
                        </div>
                        <?php
                        $element = $media->datecreated;
                        echo '<div class="wdn-subhead subhead">Added on '.date("F j, Y, g:i a", strtotime($element)).'</div>';

                        $summary = $media->description;
                        if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'summary')) {
                            $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
                        }
                        $summary = strip_tags($summary, '<a><img>');
                        $summary = str_replace('Related Links', '', $summary);
                        ?>
                        <p><?php echo $summary; ?></p>
                    </div>
                </div>
            </li>
        <?php  
        } ?>
        </ul>
        <em>Displaying <?php echo $context->first; ?> through <?php echo $context->last; ?> out of <?php echo $context->total; ?></em>
        <?php echo $pager_links; ?>
<?php
} else {
    echo "<div style='width:940px'><p>Sorry, no media could be found</p></div>";
}
?>