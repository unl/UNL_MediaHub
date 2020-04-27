<?php $baseUrl = UNL_MediaHub_Controller::getURL();
if (count($context->items)) :
?>
<h5><?php echo UNL_MediaHub::escape($context->label); ?></h5>
<div class="channels">
    <?php foreach ($context->items as $channel): ?>
    	<?php $feed_url = UNL_MediaHub_Controller::getURL($channel); ?>
		<a href="<?php echo $feed_url ?>" title="<?php echo UNL_MediaHub::escape($channel->description); ?>">
		    <div class="mh-channel-thumb mh-featured-channel dcf-txt-center">
                <div>
                    <?php if($channel->hasImage()): ?>
                        <img
                        src="<?php echo $feed_url; ?>/image"
                        alt="<?php echo UNL_MediaHub::escape($channel->title); ?> Image">
                    <?php else: ?>
                        <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg">
                            <img src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon-white.png" alt="<?php echo UNL_MediaHub::escape($channel->title); ?> Image">
                        </object>
                    <?php endif; ?>
                </div>
            </div>
		    <div class="mh-video-label dcf-txt-center unl-font-sans">
		                <span class="title"><?php echo UNL_MediaHub::escape($channel->title); ?></span>
		    </div>
		</a>
    <?php endforeach; ?>
</div>
<?php endif; ?>