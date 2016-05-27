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

<?php 

if(!empty($context->options['orderby'])){

    $label = ($context->options['orderby'] == 'datecreated') ? 'Recent' : 'Popular';

};

if(!empty($context->options['f'])){

    $label .= ($context->options['f'] == 'audio') ? ' Audio' : ' Video';

}else{

    $label .= ' Media';

};

?>

<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-bottom">
        <div class="mh-list-header">
            <div class="wdn-grid-set">
                <div class="bp2-wdn-col-three-fourths">
                    <?php if ($context->options['filter']->getType() == 'search'): ?>
                        <h1 class="wdn-brand clear-top">
                            <span class="wdn-subhead">Search results for</span>
                            <?php echo htmlentities($context->options['filter']->getValue()) ?>
                        </h1>
                    <?php elseif ($context->options['filter']->getType() == 'feed'): ?>
                        <h2 class="wdn-brand clear-top"><?php echo $label ?></h2>
                    <?php else: ?>
                        <h1 class="wdn-brand clear-top"><?php echo $label ?></h1>
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
    </div>
</div>

<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-top wdn-inner-padding-sm">

        <?php
        $buttons = (object)[];
        $buttons->selected_key = $context->options['orderby'];
        $buttons->group_id = 'orderby';
        $buttons->label = 'Order by:';
        $buttons->buttons = [
            'datecreated' => [
                'label' => 'Recent',
                'url' => $context->getURL(array('orderby' => 'datecreated', 'order' => 'DESC'))
            ],
            'title_a_z' => [
                'label' => 'A-Z',
                'url' => $context->getURL(array('orderby' => 'title_a_z', 'order' => 'ASC'))
            ],
            'title_z_a' => [
                'label' => 'Z-A',
                'url' => $context->getURL(array('orderby' => 'title_z_a', 'order' => 'DESC'))
            ],
            'popular_play_count' => [
                'label' => 'Popular',
                'url' => $context->getURL(array('orderby' => 'popular_play_count', 'order' => 'DESC'))
            ],
        ];
        echo $savvy->render($buttons, 'mh-sort-filter.tpl.php');
        ?>

        <?php
        $buttons = (object)[];
        $buttons->selected_key = $context->options['f'];
        $buttons->group_id = 'filter';
        $buttons->label = 'Filter by:';
        $buttons->buttons = [
            '' => [
                'label' => 'All',
                'url' => $context->getURL(array('f' => ''))
            ],
            'audio' => [
                'label' => 'Audio',
                'url' => $context->getURL(array('f' => 'audio'))
            ],
            'video' => [
                'label' => 'Video',
                'url' => $context->getURL(array('f' => 'video'))
            ]
        ];
        echo $savvy->render($buttons, 'mh-sort-filter.tpl.php');
        ?>
    </div>
</div>



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

    <?php if ($feeds && count($feeds->items) && $context->pager->getPage() < 2): ?>

        <div class="wdn-band wdn-light-triad-band">
            <div class="wdn-inner-wrapper wdn-inner-padding-sm">

                <h2 class="wdn-brand clear-top">
                    <span class="wdn-subhead">Channel Search</span>
                </h2>
                <ul class="mh-channel-buttons">
                    <?php foreach ($feeds->items as $feed): ?>
                        <li><a class="wdn-button wdn-button-complement" href="<?php echo UNL_MediaHub_Controller::getURL($feed); ?>"><span class="wdn-icon wdn-icon-rocket"></span><?php echo $feed->title ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>       

    <?php endif ?>


    <div class="wdn-band mh-media">
        <div class="wdn-inner-wrapper wdn-inner-padding-sm">
            <ul class="bp2-wdn-grid-set-thirds wdn-grid-clear mh-media-list<?php echo $mediaListClass ?>">
                <?php foreach ($context->items as $media): ?>
                    <li class="wdn-col">
                        <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php echo $pager_links; ?>

        </div>
    </div>          
<?php else: ?>
    <div class="wdn-band mh-media">
        <div class="wdn-inner-wrapper wdn-inner-padding-sm">
            <p>Sorry, no media could be found</p>
        </div>
    </div>
<?php endif; ?>


