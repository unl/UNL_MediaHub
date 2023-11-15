<?php

$height = 540;
$width  = 960;

$dimensions = $context->getVideoDimensions();
if (isset($dimensions['width'])) {
    // Scale everything down to 450 wide
    $height = round(($width/$dimensions['width'])*$dimensions['height']);
}

$autoplay = 'autoplay muted';
if (isset($controller->options['muted'])) {
    switch (strtolower($controller->options['muted'])) {
        // Treat these strings as false and remove muted attribute from $autoplay
        case '0':
        case 'false':
            $autoplay = 'autoplay';
    }
}

if ($parent->context instanceof UNL_MediaHub_Media_Preview) {
    $autoplay = '';
}

$preload = '';
if (isset($controller->options['preload']) && in_array($controller->options['preload'], array('auto', 'metadata', 'none'))) {
    $preload = 'preload="'.$controller->options['preload'].'"';
}

//Don't auto play on the addmedia view
if (isset($controller->options['view']) && $controller->options['view'] == 'addmedia') {
    $autoplay = '';
}

if (isset($controller->options['autoplay']) && !$controller->options['autoplay']) {
    $autoplay = '';
}

$start_language = '';
if (isset($controller->options['captions'])) {
    $start_language = 'data-start-language="'.UNL_MediaHub::escape($controller->options['captions']).'"';
}

// This was added after the fact, I did not want to break the functionality of what was already here
$muted = '';
if (isset($controller->options['muted']) && empty($autoplay)) {
    switch (strtolower($controller->options['muted'])) {
        case '1':
        case 'true':
            $muted = 'muted';
    }
}

?>
<video class="mh_player video-js" height="100" width="100" style="width:100%;height:100%" <?php echo $autoplay ?> <?php echo $muted; ?> <?php echo $preload ?> src="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getMediaURL()); ?>" controls data-mediahub-id="<?php echo (int)$context->id ?>" data-url="<?php echo $controller->getURL($context); ?>" poster="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getThumbnailURL()); ?>" title="<?php echo UNL_MediaHub::escape($context->title); ?>" <?php echo $start_language ?>>
    <?php if ($context->hasHLS()): ?>
        <source src="<?php echo $context->getHLSPlaylistUrl() ?>" type="application/x-mpegURL">
    <?php endif; ?>
    <source src="<?php echo $context->getMediaURL(); ?>" type='video/mp4'>
    <?php foreach ($context->getTextTrackURLs() as $lang=>$track):?>
        <track id="mediahub-track-<?php echo UNL_MediaHub::escape($lang) ?>" src="<?php echo UNL_MediaHub::escape(UNL_MediaHub_Controller::toAgnosticURL($track)) ?>" kind="captions" srclang="<?php echo UNL_MediaHub::escape($lang) ?>" />
    <?php endforeach ?>
</video>
