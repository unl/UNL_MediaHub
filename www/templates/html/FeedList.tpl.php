<?php
/**
 * @var $context UNL_MediaHub_FeedList
 */

$label = 'All Channels';
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | MediaHub | '.$context->label);
    $controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    $label = $context->label;
}
?>
<div class="mh-feeds">
    <div class="mh-list-header">
        <h1 class="wdn-brand"><?php echo $label; ?></h1>
        <?php if (count($context->items) && $context->pager->getLastPage() > 1): ?>
            <p>Page <?php echo $context->pager->getPage() ?> of <?php echo $context->pager->getLastPage() ?></p>
        <?php endif; ?>
    </div>
    <?php if (count($context->items)): ?>
        <?php 
        $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
            new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                    htmlentities(UNL_MediaHub_Controller::getURL($context, array_merge($context->options, array('page'=>'{%page_number}')))));
        $pager_links = $pager_layout->display(null, true);
        ?>
        <p class="mh-sort-options">
            <a href="?orderby=datecreated&amp;order=DESC" class="wdn-button wdn-button-brand">Most Recent</a>
            <a href="?orderby=plays&amp;order=DESC" class="wdn-button wdn-button-brand">Most Viewed</a>
        </p>
        <ul class="mh-feed-list">
        <?php foreach ($context->items as $feed): ?>
            <li>
                <?php $url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES) ?>
                <h2 class="wdn-brand"><a href="<?php echo $url ?>"><?php echo htmlentities($feed->title) ?></a></h2>
                <?php echo $savvy->render($feed, 'Feed/Creator.tpl.php') ?>
                <div class="wdn-grid-set">
                    <div class="bp2-wdn-col-one-fourth wdn-pull-right">
                        <a href="<?php echo $url ?>"><img src="<?php echo $url ?>/image" alt="<?php echo htmlentities($feed->title, ENT_QUOTES) ?> image" /></a>
                    </div>
                    <div class="bp2-wdn-col-three-fourths">
                        <p><?php echo htmlentities($feed->description) ?></p>
                        <?php $feed->getStats() ?>
                        <div class="wdn-grid-set">
                            <div class="bp2-wdn-col-two-thirds mh-media-samples">
                                <?php echo $savvy->render($feed->getMediaList(), 'CompactMediaList.tpl.php') ?>
                            </div>
                            <div class="bp2-wdn-col-one-third mh-feed-stats">
                                <?php echo $savvy->render($feed, 'Feed/Stats.tpl.php') ?>
                            </div>
                        </div>
                    </div>
                </div>
        		<p class="mh-more-info wdn-sans-serif"><a href="<?php echo $url ?>">See channel…</a></p>
            </li>
        <?php endforeach; ?>
        </ul>
        
        <?php echo $pager_links; ?>
    <?php else: ?>
        <p>Sorry, I could not find any channels.</p>
    <?php endif; ?>
</div>
