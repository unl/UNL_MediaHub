<?php

if (!$context->feed->hasLiveStream()) {
    echo '<h3>This channel is not configured for live streaming events.</h3>';
    return;
}

$feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES);
$controller->setReplacementData('title', 'UNL | MediaHub | '.htmlspecialchars($context->feed->title). ' | Live');
// TODO: disable breadcrumbs since currently not supported in 5.0 App templates
//$controller->setReplacementData('breadcrumbs', '
//<ol>
//   <li><a href="http://www.unl.edu/">UNL</a></li>
//   <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li>
//   <li><a href="'.$feed_url.'">'.htmlspecialchars($context->feed->title).'</a></li>
//   <li>Live</li>
//</ol>');
?>
<h2><?php echo htmlspecialchars($context->feed->title); ?> Live Streaming</h2>
<div class="grid4 first">
	<div id="wdn_calendarDisplay"></div>
	<a class="archive" href="<?php echo $feed_url;?>">Archived Events</a>
</div>
<?php
$page->addScriptDeclaration("WDN.initializePlugin('events', function(){
    WDN.events.calURL = 'http://events.unl.edu/livenews/';
	WDN.events.limit  = 5;
	WDN.events.initialize();
	});");
?>

<div class="grid8">
	<div id="wdn_live_stream_wrapper">
		<span class="liveIndicator">Live</span>
		<div id="wdn_live_stream"></div>
		<script type='text/javascript'>
		WDN.loadJS('wdn/templates_3.0/scripts/plugins/swfobject/jquery.swfobject.1-1-1.min.js', function(){
			//Fallback for flash
			WDN.jQuery('#wdn_live_stream').prepend('<p>To view this video you should download <a href="http://get.adobe.com/flashplayer/">Adobe Flash Player</a> or use a browser that supports H264/WebM video.</p>');
			
			WDN.jQuery('#wdn_live_stream').flash(
				{     
					swf: WDN.template_path + 'wdn/templates_3.0/includes/swf/player5.4.swf',   
					allowfullscreen: 'true',
					allowscriptaccess: 'always',
					flashvars: {   
						'file': 'live_3.sdp',   
						'autostart': 'true',
						'streamer': 'rtmp://real.unl.edu/live_3/'
						//'image': '<?php echo UNL_MediaHub_Controller::getURL();?>templates/html/css/images/innovation_campus.jpg'
					},
					height: '358',
					width: '600',
					id: 'jwPlayer',
					name: 'jwPlayer'
				}
			);
			
		});
		</script>
<!--
		<h4>Major Innovation Campus Announcement</h4>
		<p>University officials will make a major announcement about Nebraska Innovation Campus at 10 a.m., today in the State Capitol Rotunda.</p>
-->
	</div>
</div>
