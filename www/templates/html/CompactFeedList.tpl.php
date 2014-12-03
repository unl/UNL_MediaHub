<?php $baseUrl = UNL_MediaHub_Controller::getURL();
if (count($context->items)) :
?>
<h5><?php echo $context->label; ?></h5>
<div class="channels">
    <?php foreach ($context->items as $channel): ?>
    	<?php $feed_url = UNL_MediaHub_Controller::getURL($channel); ?>
		<a href="<?php echo $feed_url ?>" title="<?php echo htmlentities($channel->description, ENT_QUOTES); ?>">
		    <div class="mh-video-thumb mh-channel-thumb mh-featured-channel wdn-center">
                <div class="mh-thumbnail-clip">
                    <?php $channelImage = file_get_contents($feed_url."/image"); ?>
                    <?php if(!$channelImage): ?>
                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg"><img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png"></object>
                    <?php else: ?>
                    <img
                        src="<?php echo $feed_url; ?>/image"
                        alt="<?php echo htmlentities($feed->title, ENT_QUOTES); ?> Image">
                    <?php endif; ?>
                </div>
            </div>
		    <div class="mh-video-label wdn-center wdn-sans-serif">
		                <span class="title"><?php echo $channel->title?></span>	       
		    </div>
		</a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

