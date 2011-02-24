function playerReady(thePlayer) {
    //start the player and JS API
    WDN.videoPlayer.createFallback.addJWListeners(document.getElementById(thePlayer.id));
};

WDN.jQuery(document).ready(function(){
	WDN.jQuery('span.embed').colorbox({inline: true, href:'#sharing', width:'600px', height:'310px'});
	WDN.jQuery('form#addTags').hide();
	WDN.jQuery('#mediaTagsAdd a[href="#"]').click(function(){
		WDN.jQuery(this).hide();
		WDN.jQuery(this).siblings('form').show(function(){
			WDN.jQuery(this).children('input[type="text"]').focus();
		});
		return false;
	});
	/*
	 * Search Box functionality
	 */
	WDN.jQuery('#wdn_app_search label').css({display: "block"});
	WDN.jQuery('#q_app').focus(function(){
		WDN.jQuery(this).siblings("label").hide();
	});
	WDN.jQuery('#q_app').blur(function(){
		if (WDN.jQuery('#q_app').val() == "") {
			WDN.jQuery(this).siblings("label").show();
		};
	}); 
});