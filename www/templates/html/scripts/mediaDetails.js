var jQuery = WDN.jQuery;
var mediaDetails = function() {
	var video;
	return {
		imageURL : 'http://itunes.unl.edu/thumbnails.php?url=',
		
		setVideoType : function(){
		
		},
		
		updateDuration : function() {
			if (!WDN.jQuery('video')[0]){
				WDN.jQuery('#itunes_duration').attr("value", mediaDetails.findDuration(WDN.videoPlayer.createFallback.getCurrentInfo("duration")));
			} else {
				WDN.log(WDN.jQuery('video')[0].duration);
				WDN.jQuery('#itunes_duration').attr("value", mediaDetails.findDuration(WDN.jQuery('video')[0].duration));
			}
		},
		
		findDuration : function(duration) {
			return mediaDetails.formatTime(duration);
		},
		
		updateThumbnail : function(currentTime) {
			WDN.jQuery('#imageOverlay').css({'height' : WDN.jQuery("#thumbnail").height()-20 +'px' ,'width' : WDN.jQuery("#thumbnail").width()-60 +'px' }).show();
			var newThumbnail = new Image();
			newThumbnail.src = mediaDetails.imageURL + '&time='+mediaDetails.formatTime(currentTime)+'&rebuild';
			WDN.log(newThumbnail.src);
			newThumbnail.onload = function(){
				WDN.jQuery('#thumbnail').attr('src', mediaDetails.imageURL );
				WDN.jQuery('#imageOverlay').hide();
			};
			
		},
		
		currentPostion : function(video) {
			currentTime = mediaDetails.formatTime(video.currentTime);
			return currentTime;
		},
		
		formatTime : function(totalSec) { //time is coming in milliseconds
			hours = parseInt( totalSec / 3600 ) % 24;
			minutes = parseInt( totalSec / 60 ) % 60;
			seconds = Math.round((totalSec % 60)*100)/100;

			return ((hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds));
		},
		
		showVideo : function(){ //video has been updated, now parse it into to markup and show the user
			WDN.jQuery('video').attr('src', WDN.jQuery("#url").val());
			thumbURL = mediaDetails.imageURL;
			WDN.jQuery('#videoDisplay param[name=flashvars]').attr('value','file='+WDN.jQuery("#url").val()+'&amp;image='+thumbURL+'&amp;volume=100&amp;controlbar=over&amp;autostart=false&amp;skin=/wdn/templates_3.0/includes/swf/UNLVideoSkin.swf');
			//use the thumbnail generated to determine width and height
			var thumbnail = new Image();
			thumbnail.src = thumbURL+'&time='+mediaDetails.formatTime(0)+'&rebuild';
			thumbnail.onload = function(){
				calcHeight = (this.height * 460)/this.width;
				WDN.jQuery('video').attr('height', calcHeight);
				WDN.jQuery('video').attr('width', 460);
				WDN.jQuery('#videoDisplay object').attr('style', 'width:460px;height:'+calcHeight);
				WDN.jQuery('#thumbnail').attr('src',mediaDetails.imageURL);
			};
			thumbnail.onerror = '';
			WDN.initializePlugin('videoPlayer');
		},
		
		showAudio : function(){
			WDN.initializePlugin('videoPlayer');
		}
	};
}();
WDN.jQuery(document).ready(function() {
    if (formView == 'edit'){ //we're editting, so hide the introduction and go straight to the form
    	WDN.jQuery("#addMedia").hide();
        WDN.jQuery("#feedlist").hide();
        WDN.jQuery("#formDetails, #formDetails form, #formDetails fieldset, #continue3").not("#addMedia").css({"display" : "block"});
        WDN.jQuery(".headline_main").css({"display" : "inline-block"});
        WDN.jQuery("#formDetails").removeClass("two_col right").addClass('four_col left');
    	mediaDetails.imageURL = mediaDetails.imageURL + escape(WDN.jQuery("#url").val());
    }
    WDN.jQuery("#videoSubmit").click(function(event) { //called when a user adds video
            unl_check = /^http:\/\/([^\/]+)\.unl\.edu\/(.*)/;
            var r = unl_check.exec(document.getElementById("url").value);
            if (r == null) {
                alert('Sorry, you must use a .unl.edu URL!');
                return false;
            }
            WDN.jQuery("#addMedia, #feedlist").slideUp(400, function() {
                WDN.jQuery("#headline_main_video").slideDown(400, function() {
                    WDN.jQuery("#maincontent form.zenform").css({"width" : "930px"}).parent("#formDetails").removeClass("two_col right");
                    WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                    WDN.jQuery("#media_url").attr("value", WDN.jQuery("#url").val());
                    WDN.jQuery(this).css('display', 'inline-block');
                });
            });
            WDN.jQuery('input[id="video"]').attr('checked', true);
            mediaDetails.imageURL = mediaDetails.imageURL + escape(WDN.jQuery("#url").val());
            mediaDetails.showVideo();
            event.preventDefault();
        });
    WDN.jQuery("#video").click(function(event) { //called when a user adds video
        WDN.jQuery("#headline_main_audio").slideUp(400);
        WDN.jQuery("#addMedia, #feedlist").slideUp(400, function() {
            WDN.jQuery("#headline_main_video").slideDown(400, function() {
                WDN.jQuery("#maincontent form.zenform").css({"width" : "930px"}).parent("#formDetails").removeClass("two_col right");
                WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                WDN.jQuery("#media_url").attr("value", WDN.jQuery("#url").val());
                WDN.jQuery(this).css('display', 'inline-block');
            });
        });
        WDN.jQuery('input[id="video"]').attr('checked', true);
        mediaDetails.imageURL = mediaDetails.imageURL + escape(WDN.jQuery("#url").val());
        mediaDetails.showVideo();
    });
    WDN.jQuery("#audioSubmit").click(function(event) { //called when a user adds audio
        unl_check = /^http:\/\/([^\/]+)\.unl\.edu\/(.*)/;
        var r = unl_check.exec(document.getElementById("url").value);
        if (r == null) {
            alert('Sorry, you must use a .unl.edu URL!');
            return false;
        }
        WDN.jQuery("#addMedia, #feedlist").slideUp(400, function() {
            WDN.jQuery("#headline_main_audio").slideDown(400, function() {
                WDN.jQuery("#maincontent form.zenform").css({"width" : "930px"}).parent("#formDetails").removeClass("two_col right");
                WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                WDN.jQuery("source").attr("src", WDN.jQuery("#url").val());
                WDN.jQuery(this).css('display', 'inline-block');
            });
        });
        if(WDN.jQuery('.audioplayer audio').length == 0){
	        var elem = WDN.jQuery('<audio preload="auto"> \
	        		<source src="' + WDN.jQuery("#url").val() + '" type="audio/mpeg"> \
					<div class="fallback"> \
						<div class="fallback-text"> \
							<p>Please use a modern browser or install <a href="http://get.adobe.com/flashplayer/">Flash-Plugin</a></p> \
						</div> \
					</div> \
				</audio> ');
	        WDN.jQuery('.audioplayer').append(elem); // put it into the DOM
        }
        WDN.jQuery('input[id="audio"]').attr('checked', true);
        mediaDetails.showAudio();
        event.preventDefault();
    });
    WDN.jQuery("#audio").click(function(event) { //called when a user adds audio
        WDN.jQuery("#headline_main_video").slideUp(400);
        WDN.jQuery("#addMedia, #feedlist").slideUp(400, function() {
            WDN.jQuery("#headline_main_audio").slideDown(400, function() {
                WDN.jQuery("#maincontent form.zenform").css({"width" : "930px"}).parent("#formDetails").removeClass("two_col right");
                WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                WDN.jQuery("source").attr("src", WDN.jQuery("#url").val());
                WDN.jQuery(this).css('display', 'inline-block');
            });
        });
        if(WDN.jQuery('.audioplayer audio').length == 0){
	        var elem = WDN.jQuery('<audio preload="auto"> \
	        		<source src="' + WDN.jQuery("#url").val() + '" type="audio/mpeg"> \
					<div class="fallback"> \
						<div class="fallback-text"> \
							<p>Please use a modern browser or install <a href="http://get.adobe.com/flashplayer/">Flash-Plugin</a></p> \
						</div> \
					</div> \
				</audio> ');
	        WDN.jQuery('.audioplayer').append(elem); // put it into the DOM
        }
        WDN.jQuery('input[id="audio"]').attr('checked', true);
        mediaDetails.showAudio();
    });
    WDN.jQuery('a#setImage').click(function(){
    	if (!WDN.jQuery('video')[0]){
    		currentTime = WDN.videoPlayer.createFallback.getCurrentInfo("position") + .01;
    	} else {
    		currentTime = WDN.jQuery('video')[0].currentTime;
    	}
    	mediaDetails.updateThumbnail(currentTime);
    	return false;
    });
    
    //deal with the outpost extra information
    WDN.jQuery("#itunes_header ol").hide();
    WDN.jQuery("#mrss_header ol").hide();
    
    WDN.jQuery("#itunes_header legend").click(function() {
      WDN.jQuery("#itunes_header ol").toggle(400);
      return false;
    });
    WDN.jQuery("#mrss_header legend").click(function() {
      WDN.jQuery("#mrss_header ol").toggle(400);
      return false;
    });
    
    WDN.jQuery('span.embed').colorbox({inline: true, href:'#sharing', width:'600px', height:'310px'});
});
WDN.loadJS("templates/scripts/tiny_mce/jquery.tinymce.js", function() {
    WDN.jQuery("textarea#description").tinymce({
            // Location of TinyMCE script
            script_url : "templates/scripts/tiny_mce/tiny_mce.js",
            theme : "advanced",
            skin : "unl",
            
            // Theme options
	        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,|,bullist,numlist,|,link,unlink,anchor,|,removeformat,cleanup,help,code,styleselect,formatselect",
	        theme_advanced_buttons2 : "",
	        theme_advanced_buttons3 : "",
	        theme_advanced_toolbar_location : "top",
	        theme_advanced_toolbar_align : "left",
	        theme_advanced_statusbar_location : "bottom",
	        theme_advanced_resizing : true,
            theme_advanced_row_height : 33
    });
});