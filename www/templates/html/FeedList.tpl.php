<?php
/**
 * @var $context UNL_MediaHub_FeedList
 */
$baseUrl = UNL_MediaHub_Controller::getURL();
$label = 'All Channels';
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | MediaHub | '.$context->label);
    $controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ul>');
    $label = $context->label;
}
?>


<?php 

if($context->options['orderby'] == 'datecreated'){

    $label = 'Recent Channels';

}elseif($context->options['orderby'] == 'plays'){

    $label = 'Popular Channels';

};

?>

<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-bottom">
        <div class="mh-list-header">
            <div class="wdn-grid-set">
                <div class="bp2-wdn-col-three-fourths">
                    <h1 class="wdn-brand clear-top"><?php echo $label; ?></h1>
                    <?php if (count($context->items) && $context->pager->getLastPage() > 1): ?>
                        <p>Page <?php echo $context->pager->getPage() ?> of <?php echo $context->pager->getLastPage() ?></p>
                    <?php endif; ?>
                </div>
                <div class="bp2-wdn-col-one-fourth">
                    <?php echo $savvy->render($context->options['filter'], 'SearchBox.tpl.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-none">
        <?php
        $buttons = (object)[];
        $buttons->selected_key = $context->options['orderby'];
        $buttons->group_id = 'order';
        $buttons->label = 'Order by:';
        $buttons->buttons = [
            'title' => [
                'label' => 'Alphabetical',
                'url' => '/channels/'
            ],
            'datecreated' => [
                'label' => 'Recent',
                'url' => '?orderby=datecreated&order=DESC'
            ],
            'plays' => [
                'label' => 'Popular',
                'url' => '?orderby=plays&order=DESC'
            ]
        ];
        echo $savvy->render($buttons, 'mh-sort-filter.tpl.php');
        ?>
    </div>
</div>

<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-sm">
        <div class="mh-feeds">
            <?php if (count($context->items)): ?>
                <?php 
                $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
                    new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                    htmlentities(UNL_MediaHub_Controller::getURL($context, array_merge($context->options, array('page'=>'{%page_number}')))));
                $pager_links = $pager_layout->display(null, true);
                ?>
                <ul class="mh-feed-list">
                    <?php foreach ($context->items as $feed): ?>
                        <li>
                            <?php $url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES) ?>
                            <h2 class="wdn-brand"><a href="<?php echo $url ?>"><?php echo htmlentities($feed->title) ?></a></h2>
                            <div class="wdn-grid-set">
                                <div class="bp2-wdn-col-one-fourth wdn-pull-right">
                                    <a href="<?php echo $url ?>">
                                    <div class="mh-channel-thumb wdn-center">
                                        <?php if($feed->hasImage()): ?>
                                            <img
                                            src="<?php echo $url; ?>/image"
                                            alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                                        <?php else: ?>
                                            <div>
                                                <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                                                    <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                                                </object>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    </a>
                                </div>
                                <div class="bp2-wdn-col-three-fourths">
                                    <p><?php echo htmlentities($feed->description) ?></p>
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
    </div>
</div>