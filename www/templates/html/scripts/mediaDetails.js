var jQuery = WDN.jQuery;
var mediaDetails = function() {
	var video;
	return {
		setVideoType : function(){
		
		},
		
		getImageURL: function() {
		    return front_end_baseurl + 'media/' + WDN.jQuery(mejs.players.mep_0.media).attr('data-mediahub-id') + '/image';
		},
		
		updateDuration : function() {
            WDN.jQuery('#itunes_duration').attr('value', mediaDetails.findDuration(mejs.players.mep_0.media.duration));
		},
		
		findDuration : function(duration) {
			return mediaDetails.formatTime(duration);
		},
		
		updateThumbnail : function(currentTime) {
			WDN.jQuery('#imageOverlay').show();
			
			var src = mediaDetails.getImageURL() + '?time='+mediaDetails.formatTime(currentTime)+'&rebuild';
			
			WDN.jQuery.ajax(src).always(function() {
	    		WDN.jQuery('#thumbnail').attr('src', src.replace('&rebuild', ''));
	    		WDN.jQuery('#imageOverlay').hide();
	    	});
		},
		
		currentPostion : function(video) {
			return mediaDetails.formatTime(video.currentTime);
		},
		
		formatTime : function(totalSec) { //time is coming in milliseconds
		    WDN.log(totalSec);
			hours = parseInt( totalSec / 3600 ) % 24;
			minutes = parseInt( totalSec / 60 ) % 60;
			seconds = ((totalSec % 60)*100)/100;
            fraction = Math.round((seconds - Math.floor(seconds)) * 100);
            seconds = Math.floor(seconds);

			return ((hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds) + "." + fraction);
		},

		/**
		 * Check if the given URL meets requirements
		 * 
		 * @return bool
		 */
		validURL : function(url) {
			unl_check = /^http:\/\/([^\/]+)\.unl\.edu\/(.*)/;
	        var r = unl_check.exec(url);
	        if (r == null) {
	            alert('Sorry, you must use a .unl.edu URL, or upload a valid file.');
	            return false;
	        }
	        return true;
		},

		scalePlayer: function() {
			// Now scale down the player
			var thumbnail = new Image();
			thumbnail.src = WDN.jQuery('#thumbnail').attr('src')+'?time='+mediaDetails.formatTime(0);
			thumbnail.onload = function(){
				var calcHeight = (this.height * 460)/this.width;
				WDN.jQuery('#videoDisplay object').attr('style', 'width:460px;height:'+calcHeight);
				WDN.jQuery('#thumbnail').attr('src', thumbnail.src);
			};
			thumbnail.onerror = '';
		},
        
        hidePosterPicker: function() {
            WDN.jQuery('#poster_picker').hide();
            WDN.jQuery('#poster_picker_disabled').show();
        },
        
        showPosterPicker: function() {
            WDN.jQuery('#poster_picker').show();
            WDN.jQuery('#poster_picker_disabled').hide();
            mediaDetails.updateThumbnail();
        }
	};
}();

WDN.jQuery(document).ready(function() {
    if (formView == 'edit'){ //we're editting, so hide the introduction and go straight to the form
    	
        WDN.jQuery("#feedlist").hide();
        WDN.jQuery("#formDetails, #formDetails form, #formDetails fieldset, #continue3").not("#addMedia").css({"display" : "block"});
        //WDN.jQuery(".headline_main").css({"display" : "inline-block"});
        WDN.jQuery(".share-video-link").css("display","none");
        WDN.jQuery("#formDetails").removeClass("two_col right").addClass('four_col left');
    	if (mediaType == 'video') {
    		mediaDetails.scalePlayer();
    	}
    	WDN.jQuery("#fileUpload").hide();
    }
    
    if (WDN.jQuery('#media_poster').val() !== '') {
        WDN.jQuery('#poster_picker').hide();
    } else {
        WDN.jQuery('#poster_picker_disabled').hide();
    }

    WDN.jQuery('#media_poster').on('keyup', function() {
        if (this.value == '') {
            mediaDetails.showPosterPicker();
        } else {
            WDN.jQuery('#thumbnail').attr('src', this.value);
            mediaDetails.hidePosterPicker();
        }
    });
    
    WDN.jQuery('#enable_poster_picker').click(function() {
        WDN.jQuery('#media_poster').val('');
        mediaDetails.showPosterPicker();
    });

    WDN.jQuery("#mediaSubmit").click(function (event) { //called when a user adds video
        // Hide the url field, user is uploading a file
        WDN.jQuery('#media_url').closest('li').hide();

        WDN.jQuery('#fileUpload').hide();

        WDN.jQuery("#addMedia, #feedlist").slideUp(400, function () {
            WDN.jQuery("#headline_main").slideDown(400, function () {
                WDN.jQuery("#media_form").show().css({"width": "930px"}).parent("#formDetails").removeClass("two_col right");
                WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                
                WDN.jQuery(this).css('display', 'inline-block');
            });
        });

    });
    
    WDN.jQuery('a#setImage').on('click', function(){
    	var currentTime;

        currentTime = mejs.players.mep_0.getCurrentTime();

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

    WDN.initializePlugin('form_validation', [function() {
        WDN.jQuery.validation.addMethod('geo_long', 'This must be a valid longitude.', {min:-180, max:180});
        WDN.jQuery.validation.addMethod('geo_lat', 'This must be a valid latitude.', {min:-90, max:90});
        WDN.jQuery('#media_form').validation({
            containerClassName: 'validation-container',
            immediate: true
        });

        WDN.jQuery('#media_form').bind('validate-form', function(event, result) {
            if (result) {
                //prevent duplicate submissions
                WDN.jQuery('#continue3').attr('disabled', 'disabled');
            }
        });
    }]);
    
    //Collapisible forms.
    WDN.jQuery('.collapsible > legend').prepend("<span class='toggle'>+</span>");
    WDN.jQuery('.collapsible > ol').hide();
    WDN.jQuery('.collapsible > legend').click(function(){
        if (WDN.jQuery(this).next('ol').is(":visible")) {
            WDN.jQuery(this).next('ol').hide(200);
            WDN.jQuery(this).find('.toggle').html('+');
        } else {
            WDN.jQuery(this).next('ol').show(200);
            WDN.jQuery(this).find('.toggle').html('-');
        }
    });

    WDN.loadJS("../tinymce/jquery.tinymce.js", function() {
        WDN.jQuery("textarea#description").tinymce({
            // Location of TinyMCE script
            script_url : "../tinymce/tiny_mce.js",
            theme : "advanced",
            skin : "unl",

            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,|,bullist,numlist,|,link,unlink,anchor,|,removeformat,cleanup,help,code,styleselect,formatselect",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_row_height : 33
        });
    });
});