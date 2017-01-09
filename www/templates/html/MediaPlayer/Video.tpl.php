<?php

$height = 540;
$width  = 960;

$dimensions = $context->getVideoDimensions();
if (isset($dimensions[0])) {
    // Scale everything down to 450 wide
    $height = round(($width/$dimensions[0])*$dimensions[1]);
}

$autoplay = 'autoplay';
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
    $start_language = 'data-start-language="'.htmlentities($controller->options['captions']).'"';
}
?>
<video class="wdn_player" height="100" width="100" style="width:100%;height:100%" <?php echo $autoplay; ?> <?php echo $preload ?> src="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getMediaURL()); ?>" controls data-mediahub-id="<?php echo $context->id ?>" data-url="<?php echo $controller->getURL($context); ?>" poster="<?php echo UNL_MediaHub_Controller::toAgnosticURL($context->getThumbnailURL()); ?>" title="<?php echo $context->title; ?>" crossorigin="anonymous" <?php echo $start_language ?>>
    <?php foreach ($context->getTextTrackURLs() as $lang=>$track):?>
        <track id="mediahub-track-<?php echo $lang ?>" src="<?php echo htmlentities(UNL_MediaHub_Controller::toAgnosticURL($track)) ?>" kind="captions" srclang="<?php echo $lang ?>" />
    <?php endforeach ?>
</video>
