<?php

$height = 529;
$width  = 940;

$dimensions = UNL_MediaHub_Media::getMediaDimensions($context->id);
if (isset($dimensions['width'])) {
    // Scale everything down to 450 wide
    $height = round(($width/$dimensions['width'])*$dimensions['height']);
}
?>
<video height="<?php echo $height; ?>" width="<?php echo $width; ?>" autoplay src="<?php echo $context->url?>" controls poster="<?php echo $context->getThumbnailURL(); ?>">
	<track src="<?php echo $context->getVideoTextTrackURL(); ?>" kind="subtitles" type="text/vtt" srclang="en" />
</video>
