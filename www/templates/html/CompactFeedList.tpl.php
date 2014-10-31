<?php
if (count($context->items)) :
?>
<h5><?php echo $context->label; ?></h5>
<div class="channels">
    <?php foreach ($context->items as $channel): ?>
		<a href="<?php echo UNL_MediaHub_Controller::getURL($channel); ?>" title="<?php echo htmlentities($channel->description, ENT_QUOTES); ?>">
		    <div class="mh-video-thumb wdn-center">
		        <div class="mh-thumbnail-clip">
		            <img src="<?php echo UNL_MediaHub_Controller::getURL($channel).'/image'; ?>" alt="<?php echo htmlentities($channel->title, ENT_QUOTES); ?>" />
		        </div>
		        <div class="mh-play-button"></div>
		    </div>
		    <div class="mh-video-label wdn-center wdn-sans-serif">
		                <span class="title"><?php echo $channel->title?></span>	       
		    </div>
		</a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

