<?php
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | Media | '.$context->label);
    $controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    $label = $context->label;
} else {
    $label = 'All Media';
}
?>

<div class="wdn-band mh-media">
    <div class="wdn-inner-wrapper">
        <div class="mh-list-header">
            <div class="wdn-grid-set">
                <div class="bp2-wdn-col-three-fourths">
                <?php if ($context->options['filter']->getType() == 'search'): ?>
                    <h1 class="wdn-brand">
                        <span class="wdn-subhead">Search results for</span>
                        <?php echo htmlentities($context->options['filter']->getValue()) ?>
                    </h1>
                <?php elseif ($parent->context instanceof UNL_MediaHub_FeedAndMedia): ?>
                    <h2 class="wdn-brand"><?php echo $label ?></h2>
                <?php else: ?>
                    <h1 class="wdn-brand"><?php echo $label ?></h1>
                <?php endif; ?>
                <?php if (count($context->items) && $context->pager->getLastPage() > 1): ?>
                    <p>Page <?php echo $context->pager->getPage() ?> of <?php echo $context->pager->getLastPage() ?></p>
                <?php endif; ?>
                </div>
                <div class="bp2-wdn-col-one-fourth">
                <?php if (in_array($context->options['filter']->getType(), array('search', 'browse'))): ?>
                    <?php echo $savvy->render($context->options['filter'], 'SearchBox.tpl.php'); ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
        
        
        <?php if (count($context->items)): ?>
            <?php
            if ($parent->context instanceof UNL_MediaHub_FeedAndMedia) {
                // Use the feed url as the base for pagination links
                $url = UNL_MediaHub_Controller::getURL(
                    $parent->context->feed,
                    array_intersect_key(array_merge($context->options, array('page'=>'{%page_number}')), array('page'=>0, 'limit'=>0, 'order'=>0, 'orderby'=>0))
                );
            } elseif ($context instanceof UNL_MediaHub_MediaList) {
                $url = UNL_MediaHub_Controller::addURLParams($context->getURL(), array('page'=>'{%page_number}'));
            } else {
                $url = UNL_MediaHub_Controller::getURL(null, array_merge($context->options, array('page'=>'{%page_number}')));
            }
        
            $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
                new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                htmlentities($url));
            $pager_links = $pager_layout->display(null, true);
            
            $mediaListClass = '';
            if ($context->options['filter']->getType() == 'browse') {
                $mediaListClass = ' mh-media-browse page-' . $context->pager->getPage();
            }
            ?>
            
            <p class="mh-sort-options">
                <a href="<?php echo $context->getURL(array('orderby' => 'datecreated', 'order' => 'DESC')) ?>" class="wdn-button wdn-button-brand">Most Recent</a>
                <a href="<?php echo $context->getURL(array('orderby' => 'play_count', 'order' => 'DESC')) ?>" class="wdn-button wdn-button-brand">Most Viewed</a>
            </p>
            <form action="">
                <input type="radio" name="f" value="audio" id="additional_filter_audio" <?php echo ($context->options['f'] == 'audio')?'checked="checked"':'' ?>><label for="additional_filter_audio">Audio</label>
                <input type="radio" name="f" value="video" id="additional_filter_video" <?php echo ($context->options['f'] == 'video')?'checked="checked"':'' ?>><label for="additional_filter_video">Video</label>
                <input type="radio" name="f" value="" id="additional_filter_all" <?php echo ($context->options['f'] == '')?'checked="checked"':'' ?>><label for="additional_filter_all">All</label>
                <input type="hidden" name="q" value="<?php echo (isset($context->options['q']))?$context->options['q']:'';?>">
                <input type="submit" value="Filter"/>
            </form>
            
            <ul class="bp2-wdn-grid-set-thirds wdn-grid-clear mh-media-list<?php echo $mediaListClass ?>">
                <?php foreach ($context->items as $media): ?>
                    <li class="wdn-col">
                        <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
                
            <?php echo $pager_links; ?>
        <?php else: ?>
            <p>Sorry, no media could be found</p>
        <?php endif; ?>
    </div>
</div>
