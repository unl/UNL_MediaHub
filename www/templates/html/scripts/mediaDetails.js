
require(['jquery'], function($){
    var mediaDetails = function() {
        var $posterPicker = $('#poster_picker');
        var $posterPickerDisabled = $('#poster_picker_disabled');
        var $mediaPoster = $('#media_poster');
        var $mediaForm = $('#media_form');
        var $itunesHeader = $('#itunes_header');
        var $mrssHeader = $('#mrss_header');
        var $imageOverlay = $('#imageOverlay');
        var $thumbnail = $('#thumbnail');
        var $feedList = $('#feedlist');
        
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
                $imageOverlay.show();

                var src = mediaDetails.getImageURL() + '?time='+mediaDetails.formatTime(currentTime)+'&rebuild';

                $.ajax(src).always(function() {
                    $thumbnail.attr('src', src.replace('&rebuild', ''));
                    $imageOverlay.hide();
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
                thumbnail.src = $thumbnail.attr('src')+'?time='+mediaDetails.formatTime(0);
                thumbnail.onload = function(){
                    var calcHeight = (this.height * 460)/this.width;
                    $('#videoDisplay object').attr('style', 'width:460px;height:'+calcHeight);
                    $thumbnail.attr('src', thumbnail.src);
                };
                thumbnail.onerror = '';
            },

            hidePosterPicker: function() {
                $posterPicker.hide();
                $posterPickerDisabled.show();
            },

            showPosterPicker: function() {
                $posterPicker.show();
                $posterPickerDisabled.hide();
            },
            
            initialize: function() {
                $feedList.hide();
                $('#formDetails, #formDetails form, #formDetails fieldset, #continue3').not('#addMedia').css({'display' : 'block'});
                $('.share-video-link').css('display', 'none');

                if (mediaType == 'video') {
                    mediaDetails.scalePlayer();
                }

                if ($mediaPoster.val() !== '') {
                    $posterPicker.hide();
                } else {
                    $posterPickerDisabled.hide();
                }

                $mediaPoster.on('keyup', function() {
                    if (this.value == '') {
                        mediaDetails.showPosterPicker();
                    } else {
                        $thumbnail.attr('src', this.value);
                        mediaDetails.hidePosterPicker();
                    }
                });

                $('#enable_poster_picker').click(function() {
                    $mediaPoster.val('');
                    mediaDetails.showPosterPicker();
                });

                $('#setImage').on('click', function(){
                    var currentTime;

                    currentTime = mejs.players.mep_0.getCurrentTime();

                    mediaDetails.updateThumbnail(currentTime);

                    return false;
                });

                //deal with the outpost extra information
                $itunesHeader.find("ol").hide();
                $mrssHeader.find("ol").hide();

                $itunesHeader.find("legend").click(function() {
                    $itunesHeader.find("ol").toggle(400);
                    return false;
                });
                $mrssHeader.find("legend").click(function() {
                    $mrssHeader.find("ol").toggle(400);
                    return false;
                });

                WDN.initializePlugin('form_validation', [function() {
                    $.validation.addMethod('geo_long', 'This must be a valid longitude.', {min:-180, max:180});
                    $.validation.addMethod('geo_lat', 'This must be a valid latitude.', {min:-90, max:90});
                    $mediaForm.validation({
                        containerClassName: 'validation-container',
                        immediate: true
                    });

                    $mediaForm.bind('validate-form', function(event, result) {
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
            }
        };
    }();

    $(document).ready(function() {
        mediaDetails.initialize();
    });
});
