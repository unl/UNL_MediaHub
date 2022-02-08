<?php
$controller->setReplacementData('title', 'UNL | MediaHub | '.htmlspecialchars($context->feed->title));
// TODO: disable breadcrumbs since currently not supported in 5.0 App templates
//$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li><li><a href="'.UNL_MediaHub_Controller::getURL().'channels/">All Channels</a></li><li>'.htmlspecialchars($context->feed->title).'</li></ol>');
$feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES);
$baseUrl = UNL_MediaHub_Controller::getURL();
$user = UNL_MediaHub_AuthService::getInstance()->getUser();
?>
<div class="dcf-bleed dcf-pt-6 dcf-pb-6 unl-bg-lightest-gray mh-feed-info">
    <div class="dcf-wrapper">

      <div class="dcf-float-right mh-rss"><a href="?format=xml" title="RSS feed for this channel"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_RSS_BOX, '{"size": 4}'); ?></a></div>
      <h2><?php echo UNL_MediaHub::escape($context->feed->title) ?></h2>
      <?php if ($user && $context->feed->userCanEdit($user)): ?>
        <ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo (int)$context->feed->id ?>">Edit Channel</a>
            </li>
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedstats&amp;feed_id=<?php echo (int)$context->feed->id ?>">View Channel Stats</a>
            </li>
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo (int)$context->feed->id ?>">Edit Channel Users</a>
            </li>
        </ul>
      <?php endif ?>

      <div class="dcf-grid dcf-col-gap-vw dcf-pt-6">
        <div class="dcf-col-100% dcf-col-25%-start@sm">
          <div class="dcf-ratio dcf-ratio-16x9 mh-channel-thumb">
              <?php $url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES) ?>
              <?php if($context->feed->hasImage()): ?>
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
          <div class="mh-feed-stats">
              <?php echo $savvy->render($context->feed, 'Feed/Stats.tpl.php') ?>
          </div>
        </div>
        <div class="dcf-col-100% dcf-col-75%-end@sm">
          <p><?php echo UNL_MediaHub::escape($context->feed->description) ?></p>
        </div>
      </div>
    </div>
</div>

<?php echo $savvy->render($context->media_list); ?>

<?php
$maintainers = $context->feed->getUserList();
$uids = array();
foreach ($maintainers->items as $maintainer) {
    $uid = htmlentities($maintainer->uid, ENT_QUOTES);
    $uids[] = '<a href="http://directory.unl.edu/people/'.$uid.'">'.$uid.'</a>';
}
?>

<div class="dcf-bleed dcf-pt-6 unl-bg-lightest-gray mh-channel-maintainers-list">
    <div class="dcf-wrapper dcf-pt-8 dcf-pb-8">
        <em>This channel is maintained by: <?php echo implode(', ', $uids); ?></em>
    </div>
</div>
