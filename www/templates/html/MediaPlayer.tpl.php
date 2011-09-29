<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php
if (UNL_MediaHub_Media::isVideo($context->url)) {
    echo $savvy->render($context, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/Audio.tpl.php');
}
?>
<script type="text/javascript">
	if (typeof(WDN) === "undefined") {
		if (typeof(jQuery) === "undefined"){var j=document.createElement("script"); j.setAttribute("type","text/javascript"); j.setAttribute("src", "https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"); document.getElementsByTagName("head")[0].appendChild(j);}
	} else {jQuery = WDN.jQuery;}
	var c=document.createElement("link"); c.setAttribute("type", "text/css"); c.setAttribute("rel", "stylesheet"); c.setAttribute("href", "http://www.unl.edu/wdn/templates_3.0/css/content/mediaelement.css"); document.getElementsByTagName("head")[0].appendChild(c);
	window.onload=function(){jQuery.getScript('http://www.unl.edu/wdn/templates_3.0/scripts/mediaelement.js', function(){jQuery('video,audio').mediaelementplayer();});};
</script>