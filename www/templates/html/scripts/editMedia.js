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

            formatDateForDB : function(date) {
                var d = new Date(date + ' 00:00:00');
                if (d == 'Invalid Date') {
                    return '';
                }

                var month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
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

                document.getElementById('media_form').addEventListener('submit', function(e) {
                    var submitBtn = document.getElementById('continue3');
                    submitBtn.setAttribute('disabled', 'disabled');

                    var deleteBtn = document.getElementById('delete-media');
                    deleteBtn.setAttribute('disabled', 'disabled');

                    var errors = [];

                    // Validate Title
                    var title = document.getElementById('title').value.trim();
                    if (!title) {
                        errors.push('Title is required.');
                    }

                    // Validate Author
                    var author = document.getElementById('author').value.trim();
                    if (!author) {
                        errors.push('Author is required.');
                    }

                    // Validate Description
                    var description = document.getElementById('description').value.trim();
                    if (!description) {
                        errors.push('Description is required.');
                    }

                    // Validate Privacy
                    var privacy = document.getElementById('privacy').value.trim();
                    if (!privacy) {
                        errors.push('Privacy is required.');
                    }

                    // Validate Feed
                    var feedChecked = false;
                    var newFeed = document.getElementById('new_feed').value.trim();
                    var feeds = document.getElementsByName('feed_id[]');
                    for (var i=0; i<feeds.length; i++) {
                        if (feeds[i].checked === true) {
                            feedChecked = true;
                            break;
                        }
                    }
                    if (!feedChecked && !newFeed) {
                        errors.push('Media must have at least one Channel.');
                    }

                    // Validate Geo Lat/Long
                    var geoLat = document.getElementById('geo_lat').value.trim();
                    if (geoLat && (isNaN(geoLat) || Math.abs(geoLat) > 90)) {
                       errors.push('Invalid Geo Location Latitude value. Must be between -90 and 90');
                    }
                    var geoLong = document.getElementById('geo_long').value.trim();
                    if (geoLong && (isNaN(geoLong) || Math.abs(geoLong) > 180)) {
                        errors.push('Invalid Geo Location Longitude value. Must be between -180 and 180');
                    }

                    if ((geoLat && !geoLong) || (!geoLat && geoLong)) {
                        errors.push('Must provide both a Geo Location Latitude and Longitude.');
                    }

                    var mediaCreationDate = document.getElementById('media_creation_date');
                    if (mediaCreationDate.value.trim()) {
                        var formattedDate = mediaDetails.formatDateForDB(mediaCreationDate.value.trim());
                        if (!formattedDate) {
                            errors.push('Invalid Media Creation Date.');
                        } else {
                            mediaCreationDate.value = formattedDate;
                        }
                    }

                    // Submit form or display errors
                    if (errors.length > 0) {
                        e.preventDefault();
                        var mediaErrorsContainer = document.getElementById('media-errors');
                        var mediaErrorsList = document.getElementById('media-errors-list');
                        mediaErrorsList.innerHTML = '';
                        for (var i=0; i<errors.length; i++) {
                            var errorItem = document.createElement('li');
                            errorItem.innerHTML = errors[i];
                            mediaErrorsList.appendChild(errorItem);
                        }
                        submitBtn.removeAttribute('disabled');
                        deleteBtn.removeAttribute('disabled');
                        mediaErrorsContainer.style.display = 'block';
                        mediaErrorsContainer.scrollIntoView();
                    }
                });

                document.getElementById('geo_location').addEventListener('toggleElementOn', function(){
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
                }, true);

                // $('#geo_location').on('toggleElementOn', 'div', function() {
                    
                // });

                //Update duration
                $('.find-duration').click(function() {
                    // mediaDetails.updateDuration();
                    //Prevent default action
                    return false;
                });
            
                window.addEventListener("message", function(event){
                    if(event.data.currentTime != undefined){                        
                        currentTime = event.data.currentTime;
                    }
                }, false);

            }
        };
    }();

    $(document).ready(function() {
        mediaDetails.initialize();
    });
});
