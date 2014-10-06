<div class="parallax-parent wdn-band" id="apply">
        <div class="parallax-container">
            <div class="parallax-image band-020">
                <div id="video-container">
                    <video id="video-player" preload="metadata" autoplay="autoplay" loop="" class="fillWidth">
                        <source src="http://admissions.unl.edu/includes/videos/apply/apply-loop.mp4" type="video/mp4">
                        <source src="http://admissions.unl.edu/includes/videos/apply/apply-loop.webm" type="video/webm">
                        <source src="http://admissions.unl.edu/includes/videos/apply/apply-loop.ogg" type="video/ogg">
                        Your browser does not support the video tag. I suggest you upgrade your browser.
                    </video>
                </div>
            </div>

<div class="parallax-content">
    <div id="wdn_app_search" class="wdn-band">
         <div class="wdn-inner-wrapper wdn-inner-padding-sm ">
             <form method="get" action="'.UNL_MediaHub_Controller::getURL().'search/">
                 <div class="wdn-input-group bp3-wdn-col-one-third centered" style="position:relative;">
                     <label for="q_app">Search MediaHub</label><input id="q_app" name="q" type="text" />
                     <span class="wdn-input-group-btn "><input type="submit" class="search_submit_button" value="Go" /></span>
                 </div>
             </form>
         </div>
    </div>
 <script>
WDN.jQuery("#wdn_search_form").attr("action","'.UNL_MediaHub_Controller::getURL().'search/");
WDN.jQuery("#wdn_search_form").unbind();
WDN.jQuery("#wdn_search_query").unbind("keyup");
</script>'
                
            </div>
        </div>
    </div>


<div class="wdn-band">
    <div class="wdn-grid-set"></div>
</div>


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