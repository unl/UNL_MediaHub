<?php
$type   = 'audio';
$height = 529;
$width  = 940;
$inForm = $parent->context instanceof UNL_MediaHub_Feed_Media_Form;

if ($inForm) { 
	if ($context == 'videoDisplay') { ?>
		<div class="videoplayer">
			<video height="" width="" src="" controls ></video>
         </div>
<?php
	} else if ($context == 'audioDisplay') { ?>
         <div class="audioplayer" style="min-height:50px;"></div>
<?php 
	}
} else if (isset($context->media) || !$inForm) {
	if (UNL_MediaHub_Media::isVideo($context->type)) {
	    $type = 'video';
	    $dimensions = UNL_MediaHub_Media::getMediaDimensions($context->id);
	    if (isset($dimensions['width'])) {
	        // Scale everything down to 450 wide
	        $height = round(($width/$dimensions['width'])*$dimensions['height']);
	    }
	}
	
	if ($type == 'video') { ?>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<video height="<?php echo $height; ?>" width="<?php echo $width; ?>" autoplay src="<?php echo $context->url?>" controls poster="<?php echo $context->getThumbnailURL(); ?>"></video>
<?php } else if ($type == 'audio') { ?>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<audio preload="auto" src="<?php echo $context->url?>"></audio>
	<?php } ?>
<script type="text/javascript">
	if (typeof(WDN) === "undefined") {
		if (typeof(jQuery) === "undefined"){var j=document.createElement("script"); j.setAttribute("type","text/javascript"); j.setAttribute("src", "https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"); document.getElementsByTagName("head")[0].appendChild(j);}
	} else {jQuery = WDN.jQuery;}
	var c=document.createElement("link"); c.setAttribute("type", "text/css"); c.setAttribute("rel", "stylesheet"); c.setAttribute("href", "http://www.unl.edu/wdn/templates_3.0/css/content/mediaelement.css"); document.getElementsByTagName("head")[0].appendChild(c);
	window.onload=function(){jQuery.getScript('http://www.unl.edu/wdn/templates_3.0/scripts/mediaelement.js', function(){jQuery('video,audio').mediaelementplayer();});};
</script><?php }