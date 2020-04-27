
require(['jquery'], function($){
    var mediaDetails = function() {
        var $posterPicker;
        var $posterPickerDisabled;
        var $mediaPoster;
        var $mediaForm;
        var $itunesHeader;
        var $mrssHeader;
        var $imageOverlay;
        var $thumbnail;
        var $feedList;

        var currentTime = 0;
        
        return {
            getImageURL: function() {
                return front_end_baseurl + 'media/' + $("#mediahub-iframe-embed").data('mediahub-id') + '/image';
            },

            // updateDuration : function() {
            //     $('#itunes_duration').val(mediaDetails.findDuration(mejs.players.mep_0.media.duration));
            // },

            findDuration : function(duration) {
                return mediaDetails.formatTime(duration);
            },

            updateThumbnail : function() {
                $imageOverlay.show();

                var src = mediaDetails.getImageURL() + '?time='+mediaDetails.formatTime(currentTime)+'&rebuild';

                console.log(src)

                $.ajax(src).always(function() {
                    $thumbnail.attr('src', src.replace('&rebuild', ''));
                    $imageOverlay.hide();
                });
            },

            formatTime : function(totalSec) { //time is coming in seconds
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
                $posterPicker = $('#poster_picker');
                $posterPickerDisabled = $('#poster_picker_disabled');
                $mediaPoster = $('#media_poster');
                $mediaForm = $('#media_form');
                $itunesHeader = $('#itunes_header');
                $mrssHeader = $('#mrss_header');
                $imageOverlay = $('#imageOverlay');
                $thumbnail = $('#thumbnail');
                $feedList = $('#feedlist');

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
                    mediaDetails.updateThumbnail();
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

                $('#geo_location legend').click(function() {
                    var map;
                    var marker = false;
                    var lincoln = new google.maps.LatLng(41.5299085734404, -99.591595703125);
                    var myOptions = {
                        zoom: 6,
                        center: lincoln,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

                    google.maps.event.addListener(map, 'click', function(event) {
                        if (marker != false) {
                            marker.setMap(null);
                        }
                        marker = new google.maps.Marker({
                            map: map,
                            position: event.latLng,
                            animation: google.maps.Animation.DROP
                        });
                        $('#geo_lat').attr('value', event.latLng.lat());
                        $('#geo_long').attr('value', event.latLng.lng());
                    });
                });

                //Update duration
                $('.find-duration').click(function() {
                    // mediaDetails.updateDuration();
                    //Prevent default action
                    return false;
                });
            
                window.addEventListener("message", function(event){
                    if(event.data.currentTime != undefined){                        
                        currentTime = event.data.currentTime;   
                        console.log(currentTime)
                    }
                }, false);



            }
        };
    }();

    $(document).ready(function() {
        mediaDetails.initialize();
    });
});
