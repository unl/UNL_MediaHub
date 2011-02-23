<?php
$feed_url = htmlentities(UNL_MediaYak_Controller::getURL($context->feed), ENT_QUOTES);
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | MediaHub | '.htmlspecialchars($context->feed->title). ' | Live');
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '
<ul>
    <li><a href="http://www.unl.edu/">UNL</a></li>
    <li><a href="'.UNL_MediaYak_Controller::getURL().'">MediaHub</a></li>
    <li><a href="'.$feed_url.'">'.htmlspecialchars($context->feed->title).'</a></li>
    <li>Live</li>
</ul>');
?>
<h2><?php echo htmlspecialchars($context->feed->title); ?> Live Streaming</h2>
<div id="wdn_live_stream"></div>
<script type='text/javascript'>
WDN.loadJS('wdn/templates_3.0/scripts/plugins/swfobject/jquery.swfobject.1-1-1.min.js', function(){
	//Fallback for flash
	WDN.jQuery('#wdn_live_stream').prepend('<p>To view this video you should download <a href="http://get.adobe.com/flashplayer/">Adobe Flash Player</a> or use a browser that supports H264/WebM video.</p>');
	
	WDN.jQuery('#wdn_live_stream').flash(
		{     
			swf: WDN.template_path + 'wdn/templates_3.0/includes/swf/player5.4.swf',   
			allowfullscreen: 'false',
			allowscriptaccess: 'always',
			flashvars: {   
				'file': 'myStream.sdp',   
				'autostart': 'true',
				'streamer': 'rtmp://real.unl.edu/live/'
			},
			height: '360',
			width: '640',
			id: 'jwPlayer',
			name: 'jwPlayer'
		}
	);
	
});
</script>