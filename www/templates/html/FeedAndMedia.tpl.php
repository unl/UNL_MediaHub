<?php
$controller->setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.htmlentities($context->feed->title, ENT_QUOTES).'" href="?format=xml" />');
$controller->setReplacementData('title', 'UNL | MediaHub | '.htmlspecialchars($context->feed->title));
$controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.htmlspecialchars($context->feed->title).'</li></ul>');
$feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES);
?>
<div class="wdn-band wdn-light-neutral-band mh-feed-info">
    <div class="wdn-inner-wrapper">
        <h1><?php echo htmlentities($context->feed->title) ?></h1>
        <?php echo $savvy->render($context->feed, 'Feed/Creator.tpl.php') ?>
        <div class="wdn-grid-set">
            <div class="bp2-wdn-col-one-fourth wdn-pull-right">
                <img src="<?php echo $feed_url; ?>/image" alt="<?php echo htmlentities($context->feed->title, ENT_QUOTES); ?> Image" />
            </div>
            <div class="bp2-wdn-col-three-fourths">
                <p><?php echo htmlentities($context->feed->description) ?></p>
            </div>
            <div class="bp2-wdn-col-one-fourth wdn-pull-right mh-feed-stats">
                <?php echo $savvy->render($context->feed, 'Feed/Stats.tpl.php') ?>
            </div>
            <?php if ($context->feed->userCanEdit($user)): ?>
                <a href="<?php echo UNL_MediaHub_Manager::getURL()?>?view=feedmetadata&amp;id=<?php echo $context->feed->id ?>" class="wdn-button wdn-pull-right">Edit</a>
            <?php endif ?>
        </div>
    </div>
</div>

<?php echo $savvy->render($context->media_list); ?>
