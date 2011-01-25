<?php
$type   = 'audio';
$height = 529;
$width  = 940;

if ($parent->context instanceof UNL_MediaYak_Feed_Media_Form) {
    // We use a smaller player size on the edit form
    $height = 253;
    $width = 460;
}

if (UNL_MediaYak_Media::isVideo($context->type)) {
    $type = 'video';
    $dimensions = UNL_MediaYak_Media::getMediaDimensions($context->id);
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']);
    }
}

if ($type == 'video') {
?>
<video height="<?php echo $height; ?>" width="<?php echo $width; ?>" autoplay src="<?php echo $context->url?>" controls poster="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.($context->url)?>">
    <object type="application/x-shockwave-flash" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px" data="/wdn/templates_3.0/includes/swf/player4.3.swf">
        <param name="movie" value="/wdn/templates_3.0/includes/swf/player4.3" />
        <param name="allowfullscreen" value="true" />
        <param name="allowscriptaccess" value="always" />
        <param name="wmode" value="transparent" />
        <param name="flashvars" value="file=<?php echo urlencode($context->url)?>&amp;image=<?php echo urlencode(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url))?>&amp;volume=100&amp;controlbar=over&amp;autostart=true&amp;skin=/wdn/templates_3.0/includes/swf/UNLVideoSkin.swf" /> 
    </object>
</video>
<?php 
} else if ($type == 'audio') {
?>
<div class="audioplayer"> 
    <audio preload="auto"> 
        <source src="<?php echo $context->url?>" type="audio/mpeg"> 
        <div class="fallback"> 
            <div class="fallback-text"> 
                <p>Please use a modern browser or install <a href="http://get.adobe.com/flashplayer/">Flash-Plugin</a></p> 
                <ul> 
                    <li><a class="source" href="<?php echo $context->url?>"><?php echo $context->url?></a></li> 
                </ul> 
            </div> 
        </div> 
    </audio>
    <span class="title"><?php echo $context->title; ?><span>
</div>
<?php 
}
?>
<script type="text/javascript">
WDN.initializePlugin('videoPlayer');
</script>