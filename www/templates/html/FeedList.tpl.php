<?php
/**
 * @var $context UNL_MediaHub_FeedList
 */
$baseUrl = UNL_MediaHub_Controller::getURL();
$label = 'All Channels';
if (isset($context->label) && !empty($context->label)) {
    $controller->setReplacementData('title', 'UNL | MediaHub | '.$context->label);
    // TODO: disable breadcrumbs since currently not supported in 5.0 App templates
    //$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.$context->label.'</li></ol>');
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

<div class="dcf-bleed dcf-pt-6">
    <div class="dcf-wrapper dcf-pb-0">
        <div class="mh-list-header">
            <div class="dcf-grid">
                <div class="dcf-col-100% dcf-col-75%-start@sm">
                    <h2><?php echo UNL_MediaHub::escape($label); ?></h2>
                    <?php if (count($context->items) && $context->pager->getLastPage() > 1): ?>
                        <p>Page <?php echo $context->pager->getPage() ?> of <?php echo $context->pager->getLastPage() ?></p>
                    <?php endif; ?>
                </div>
                <div class="dcf-col-100% dcf-col-25%-end@sm">
                    <?php echo $savvy->render($context->options['filter'], 'SearchBox.tpl.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="dcf-bleed">
    <div class="dcf-wrapper dcf-pb-0">
        <?php
        $buttons = (object)[];
        $buttons->selected_key = $context->options['orderby'];
        $buttons->group_id = 'order';
        $buttons->label = 'Order by:';
        $buttons->buttons = [
            'title' => [
                'label' => 'Alphabetical',
                'url' => '?orderby=title&order=ASC'
            ],
            'datecreated' => [
                'label' => 'Recent',
                'url' => '?orderby=datecreated&order=DESC'
            ],
            'plays' => [
                'label' => 'Popular',
                'url' => $baseUrl.'channels/'
            ]
        ];
        echo $savvy->render($buttons, 'mh-sort-filter.tpl.php');
        ?>
    </div>
</div>

<div class="dcf-bleed">
    <div class="dcf-wrapper dcf-pt-8 dcf-pb-8">
        <div class="mh-feeds">
            <?php if (count($context->items)): ?>
                <?php
                $pager_layout = new UNL_MediaHub_List_PagerLayout($context->pager,
                    new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
                    htmlentities(UNL_MediaHub_Controller::getURL($context, array_merge($context->options, array('page'=>'{%page_number}')))));
                $pager_links = $pager_layout->display(null, true);
                if ($theme->isCustomTheme()) {
	                $page->addScriptDeclaration("
                        require(['dcf/dcf-pagination'], function(DCFPaginationModule) {
                            const paginationNavs = document.querySelectorAll('.dcf-pagination');
                            const pagination = new DCFPaginationModule.DCFPagination(paginationNavs);
                            pagination.initialize();
                        });");
                } else {
	                $page->addScriptDeclaration("WDN.initializePlugin('pagination');");
                }
                ?>
                <ul class="mh-feed-list">
                    <?php foreach ($context->items as $feed): ?>
                        <?php $url = UNL_MediaHub::escape(UNL_MediaHub_Controller::getURL($feed)) ?>
                        <li class="dcf-mb-8">
                            <div class="dcf-grid dcf-col-gap-vw">
                                <div class="dcf-col-100% dcf-col-25%-start@sm">
                                    <a href="<?php echo $url ?>">
                                    <div class="dcf-ratio dcf-ratio-16x9 mh-channel-thumb">
                                        <?php if($feed->hasImage()): ?>
                                            <img
                                                class="dcf-ratio-child dcf-obj-fit-cover"
                                                src="<?php echo $url; ?>/image"
                                                aria-hidden="true"
                                                alt="">
                                        <?php else: ?>
                                            <div class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">
                                                <img
                                                    class="dcf-h-8 dcf-w-8"
                                                    src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg"
                                                    height="51"
                                                    width="51"
                                                    aria-hidden="true"
                                                    alt="">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                        <span class="dcf-sr-only">View Channel <?php echo htmlentities($feed->title) ?></span>
                                    </a>
                                </div>
                                <div class="dcf-col-100% dcf-col-75%-end@sm">
                                    <h2><a href="<?php echo $url ?>"><?php echo htmlentities($feed->title) ?></a></h2>
                                    <p><?php echo UNL_MediaHub::escape($feed->description) ?></p>
                                    <div class="dcf-grid dcf-col-gap-vw">
                                        <div class="dcf-col-100% dcf-col-75%-start@sm mh-media-samples">
                                            <?php echo $savvy->render($feed->getMediaList(), 'CompactMediaList.tpl.php') ?>
                                        </div>
                                        <div class="dcf-col-100% dcf-col-25%-end@sm mh-feed-stats">
                                            <?php echo $savvy->render($feed, 'Feed/Stats.tpl.php') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mh-more-info unl-font-sans"><a href="<?php echo $url ?>">See channel<span class="dcf-sr-only">: <?php echo $feed->title; ?></span>…</a></p>
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