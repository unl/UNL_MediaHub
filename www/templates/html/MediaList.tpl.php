<?php
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | Media | '.$context->label);
    $controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    $label = $context->label;
} else {
    $label = 'All Media';
}

$feeds = $context->getRelatedFeeds(array('limit'=>6));
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
                <?php elseif ($context->options['filter']->getType() == 'feed'): ?>
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

        <p class="mh-sort-filter">
            <a href="<?php echo $context->getURL(array('orderby' => 'datecreated', 'order' => 'DESC')) ?>" class="wdn-button wdn-button-brand">Most Recent</a>
            <a href="<?php echo $context->getURL(array('orderby' => 'play_count', 'order' => 'DESC')) ?>" class="wdn-button wdn-button-brand">Most Viewed</a>
            <span class="mh-btn-group">
                <a href="<?php echo $context->getURL(array('f' => '')) ?>" class="wdn-button<?php echo ($context->options['f'] == '') ? ' active' : '' ?>">All</a>
                <a href="<?php echo $context->getURL(array('f' => 'audio')) ?>" class="wdn-button<?php echo ($context->options['f'] == 'audio') ? ' active' : '' ?>">Audio</a>
                <a href="<?php echo $context->getURL(array('f' => 'video')) ?>" class="wdn-button<?php echo ($context->options['f'] == 'video') ? ' active' : '' ?>">Video</a>
            </span>
        </p>
        
        <?php if (count($context->items)): ?>
            <?php
            $url = $context->getURL(array('page'=>'{%page_number}'));
        
            $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
                new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                htmlentities($url));
            $pager_links = $pager_layout->display(null, true);
            
            $mediaListClass = '';
            if ($context->options['filter']->getType() == 'browse') {
                $mediaListClass = ' mh-media-browse page-' . $context->pager->getPage();
            }
            ?>

            <?php if ($feeds && count($feeds->items)): ?>
                <h2 class="wdn-brand">
                    <span class="wdn-subhead">Related Channels</span>
                </h2>
                <ul>
                    <?php foreach ($feeds->items as $feed): ?>
                        <li><a href="<?php UNL_MediaHub_Controller::getURL($feed) ?>"><?php echo $feed->title ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif ?>
            
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
