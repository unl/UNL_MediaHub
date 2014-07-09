<div class="wdn-grid-set">
    <div class="bp1-wdn-col-three-fourths feature">
        <div class="group">
            <h3>Media</h3>
            <ul class="switcher">
                <li>
                    <a href="#top_media" class="selected">Featured</a>
                </li>
                <li>
                    <a href="#latest_media">Latest</a>
                </li>
            </ul>
        </div>
        <div class="wdn_tabs_content">
            <div id="top_media">
                <?php echo $savvy->render($context->top_media); ?>
            </div>
            <div id="latest_media">
                <?php echo $savvy->render($context->latest_media); ?>
            </div>
        </div>
        <div id="exploreMedia">
            <h3>Explore MediaHub</h3>
            <div class="box_display">
                <div class="exp_channels">
                    <h4>Channels</h4>
                    <p>Channels are groups of related media. Any media can be part of any channel. Create your own channel of your favorite media!</p>
                    <a href="channels/">Explore all Channels</a>
                </div>
                <div class="exp_tags">
                    <h4>Tags</h4>
                    <p>Tags will allow you to find related media, build dynamic collections and organize your media!</p>
                    <p>Coming soon!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bp1-wdn-col-one-fourth">
        <a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>manager/" class="wdn-button wdn-button-triad">Add your media</a>
        <?php
        echo $savvy->render($context->featured_channels, 'CompactFeedList.tpl.php');
        ?>
        <a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/" class="wdn-button">See all channels</a>
    </div>
</div>
<script type="text/javascript">
WDN.jQuery('document').ready(function($){
	$('#latest_media').hide();
	$('ul.switcher a').click(function(e){
		if (!($(this).hasClass('selected'))) {
			$('ul.switcher a').each(function(){
				$(this).toggleClass('selected');
			});
			$('.wdn_tabs_content ' + $(this).attr('href')).toggle();
			$('.wdn_tabs_content > div').not($(this).attr('href')).hide();
		}
		e.preventDefault();
		return false;
	});
});
</script>