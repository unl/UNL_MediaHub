<?php

$height = 529;
$width  = 940;

$dimensions = $context->getVideoDimensions();
if (isset($dimensions[0])) {
    // Scale everything down to 450 wide
    $height = round(($width/$dimensions[0])*$dimensions[1]);
}
?>
<video height="<?php echo $height; ?>" width="<?php echo $width; ?>" autoplay src="<?php echo $context->url?>" controls poster="<?php echo $context->getThumbnailURL(); ?>">
	<track src="<?php echo $context->getVideoTextTrackURL(); ?>" kind="subtitles" type="text/vtt" srclang="en" />
</video>
