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

      <div class="dcf-float-right mh-rss"><a href="?format=xml" title="RSS feed for this channel"><span aria-hidden="true"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_RSS_BOX, '{"size": 4}'); ?></span></a></div>
      <h2><?php echo UNL_MediaHub::escape($context->feed->title) ?>
        <?php if ($user && $context->feed->userCanEdit($user)): ?>
          <span class="dcf-txt-base"><a href="<?php echo UNL_MediaHub_Manager::getURL()?>?view=feedmetadata&amp;id=<?php echo (int)$context->feed->id ?>" class="dcf-btn dcf-btn-primary mh-channel-edit-button">Edit</a></span>
        <?php endif ?>
      </h2>

      <div class="dcf-grid dcf-col-gap-vw dcf-pt-6">
        <div class="dcf-col-100% dcf-col-25%-start@sm">
          <div class="mh-channel-thumb dcf-txt-center">
              <?php $url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES) ?>
              <?php if($context->feed->hasImage()): ?>
                <img src="<?php echo $url; ?>/image"
                    alt="<?php echo UNL_MediaHub::escape($context->feed->title); ?> Image">
              <?php else: ?>
                <div>
                  <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                    <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo htmlentities($context->feed->title, ENT_QUOTES); ?> Image">
                  </object>
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
