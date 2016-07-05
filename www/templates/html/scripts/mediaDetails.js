var mediaDetails;

require(['jquery'], function($){
    mediaDetails = function() {
        return {
            getImageURL: function() {
                return front_end_baseurl + 'media/' + $(mejs.players.mep_0.media).attr('data-mediahub-id') + '/image';
            },

            updateDuration : function() {
                $('#itunes_duration').val(mediaDetails.findDuration(mejs.players.mep_0.media.duration));
            },

            findDuration : function(duration) {
                return mediaDetails.formatTime(duration);
            },

            updateThumbnail : function(currentTime) {
                $('#imageOverlay').show();

                var src = mediaDetails.getImageURL() + '?time='+mediaDetails.formatTime(currentTime)+'&rebuild';

                $.ajax(src).always(function() {
                    $('#thumbnail').attr('src', src.replace('&rebuild', ''));
                    $('#imageOverlay').hide();
                });
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

            scalePlayer: function() {
                // Now scale down the player
                var thumbnail = new Image();
                thumbnail.src = $('#thumbnail').attr('src')+'?time='+mediaDetails.formatTime(0);
                thumbnail.onload = function(){
                    var calcHeight = (this.height * 460)/this.width;
                    $('#videoDisplay object').attr('style', 'width:460px;height:'+calcHeight);
                    $('#thumbnail').attr('src', thumbnail.src);
                };
                thumbnail.onerror = '';
            },

            hidePosterPicker: function() {
                $('#poster_picker').hide();
                $('#poster_picker_disabled').show();
            },

            showPosterPicker: function() {
                $('#poster_picker').show();
                $('#poster_picker_disabled').hide();
            }
        };
    }();

    $(document).ready(function() {

        $('#feedlist').hide();
        $('#formDetails, #formDetails form, #formDetails fieldset, #continue3').not('#addMedia').css({'display' : 'block'});
        $('.share-video-link').css('display', 'none');
        
        if (mediaType == 'video') {
            mediaDetails.scalePlayer();
        }

        if ($('#media_poster').val() !== '') {
            $('#poster_picker').hide();
        } else {
            $('#poster_picker_disabled').hide();
        }

        $('#media_poster').on('keyup', function() {
            if (this.value == '') {
                mediaDetails.showPosterPicker();
            } else {
                $('#thumbnail').attr('src', this.value);
                mediaDetails.hidePosterPicker();
            }
        });

        $('#enable_poster_picker').click(function() {
            $('#media_poster').val('');
            mediaDetails.showPosterPicker();
        });

        $("#mediaSubmit").click(function (event) { //called when a user adds video
            // Hide the url field, user is uploading a file
            $('#media_url').closest('li').hide();

            $('#fileUpload').hide();

            $("#addMedia, #feedlist").slideUp(400, function () {
                $("#headline_main").slideDown(400, function () {
                    $("#media_form").show().css({"width": "930px"}).parent("#formDetails").removeClass("two_col right");
                    $("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);

                    $(this).css('display', 'inline-block');
                });
            });

        });

        $('a#setImage').on('click', function(){
            var currentTime;

            currentTime = mejs.players.mep_0.getCurrentTime();

            mediaDetails.updateThumbnail(currentTime);

            return false;
        });

        //deal with the outpost extra information
        $("#itunes_header ol").hide();
        $("#mrss_header ol").hide();

        $("#itunes_header legend").click(function() {
            $("#itunes_header ol").toggle(400);
            return false;
        });
        $("#mrss_header legend").click(function() {
            $("#mrss_header ol").toggle(400);
            return false;
        });

        WDN.initializePlugin('form_validation', [function() {
            $.validation.addMethod('geo_long', 'This must be a valid longitude.', {min:-180, max:180});
            $.validation.addMethod('geo_lat', 'This must be a valid latitude.', {min:-90, max:90});
            $('#media_form').validation({
                containerClassName: 'validation-container',
                immediate: true
            });

            $('#media_form').bind('validate-form', function(event, result) {
                if (result) {
                    //prevent duplicate submissions
                    $('#continue3').attr('disabled', 'disabled');
                }
            });
        }]);

        //Collapisible forms.
        $('.collapsible > legend').prepend("<span class='toggle'>+</span>");
        $('.collapsible > ol').hide();
        $('.collapsible > legend').click(function(){
            if ($(this).next('ol').is(":visible")) {
                $(this).next('ol').hide(200);
                $(this).find('.toggle').html('+');
            } else {
                $(this).next('ol').show(200);
                $(this).find('.toggle').html('-');
            }
        });
        
        //Update duration
        $('.find-duration').click(function() {
            mediaDetails.updateDuration();
            //Prevent default action
            return false;
        });
    });
});