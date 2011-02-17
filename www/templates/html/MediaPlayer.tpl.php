<?php
$type   = 'audio';
$height = 529;
$width  = 940;
$inForm = $parent->context instanceof UNL_MediaYak_Feed_Media_Form;

if ($inForm) { ?>
		<video height="" width="" src="" controls >	 
             <object type="application/x-shockwave-flash" data="/wdn/templates_3.0/includes/swf/player4.3.swf">	 
                 <param name="movie" value="/wdn/templates_3.0/includes/swf/player4.3" />	 
                 <param name="allowfullscreen" value="true" />	 
                 <param name="allowscriptaccess" value="always" />	 
                 <param name="wmode" value="transparent" />	 
                 <param name="flashvars" value="" />	 
             </object>
         </video>
         <div class="audioplayer" style="min-height:50px;"></div>
<?php 
} else if (isset($context->media) || !$inForm) {
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
	    <span class="title"><?php echo $context->title; ?></span>
	</div>
	<?php 
	} ?>
<script type="text/javascript">
if (typeof(WDN) == "undefined") {
	if (typeof(jQuery) == "undefined"){var j=document.createElement("script"); j.setAttribute("type","text/javascript"); j.setAttribute("src", "http://www.unl.edu/wdn/templates_3.0/scripts/jquery.js"); document.getElementsByTagName("head")[0].appendChild(j);}
	var s=document.createElement("script"); s.setAttribute("type","text/javascript"); s.setAttribute("src", "http://www.unl.edu/wdn/templates_3.0/scripts/wdn.js"); var c=document.createElement("link"); c.setAttribute("type", "text/css"); c.setAttribute("rel", "stylesheet"); c.setAttribute("href", "http://www.unl.edu/wdn/templates_3.0/css/content/videoPlayer.css"); document.getElementsByTagName("head")[0].appendChild(c); document.getElementsByTagName("head")[0].appendChild(s);
	window.onload=function(){WDN.jQuery=jQuery.noConflict(true); WDN.template_path="http://www.unl.edu/"; WDN.initializePlugin("videoPlayer"); WDN.initializePlugin("analytics");};
} else {WDN.initializePlugin("videoPlayer");}
</script>
<?php 
} ?>