<?php
/**
 * @var $context UNL_MediaHub_DefaultHomepage
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<div class="dcf-bleed dcf-overflow-hidden dcf-bg-cover mh-search-container">
  <div class="mh-search-bg-video">
    <video class="dcf-d-block dcf-w-100%" id="video-player" preload="metadata" autoplay="autoplay"  loop="" muted>
        <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.mp4" type="video/mp4">
        <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.webm" type="video/webm">
        <source src="<?php echo $baseUrl ?>templates/html/featured-video/blurdarklights.ogv" type="video/ogg">
        Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
  </div>
  <div class="dcf-absolute dcf-h-100% dcf-w-100% dcf-pin-top dcf-pin-left dcf-d-flex dcf-ai-center dcf-jc-center dcf-wrapper mh-search">
    <form class="dcf-w-max-lg dcf-form dcf-form-controls-inline" method="get" action="<?php echo $baseUrl ?>search/">
      <label class="dcf-inverse" for="q_app">Search MediaHub</label>
      <div class="dcf-input-group">
        <input class="dcf-bg-transparent dcf-inverse" id="q_app" name="q" type="text" required />
        <input class="dcf-btn dcf-btn-inverse-primary" type="submit" value="Go" />
      </div>
    </form>
  </div>
</div>
<div class="dcf-bleed dcf-wrapper dcf-pt-7 dcf-pb-6 unl-bg-lightest-gray">
  <h2 class="dcf-txt-center dcf-subhead dcf-mb-6">Popular Videos</h2>
  <div class="dcf-grid-halves@sm dcf-grid-thirds@md dcf-col-gap-vw dcf-row-gap-6">
    <?php foreach ($context->getTopMedia() as $media): ?>
      <div>
        <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php echo $theme->renderThemeTemplate(null, 'homepage-body-nav.tpl.php'); ?>
