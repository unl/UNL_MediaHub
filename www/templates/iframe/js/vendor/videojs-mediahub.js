videojs.registerPlugin('MediahubPlayer', MediahubPlayer);

function MediahubPlayer(options) {
    options = (typeof options !== 'undefined') ? options : {};

    // show captions by default by boolean options.captions
    var captions = (typeof options.captions !== 'undefined') ? options.captions : false;

    var isEmbed = !canAccessParent();

    if (!isEmbed && window.parent.document === window.document) {
        //Parent document is the same as this document, so... this is an embed.
        isEmbed = true;
    }

    if (!isEmbed) {
        var $parentDocument = $(window.parent.document);
    }

    var t = this;
    var v = this.el();

    var $transcript;
    var $captionSearch;
    var $video = $(v);
    var mediahub_id = $video.attr('data-mediahub-id');
    var $mhLanguageSelect;

    var Safari = false;
    if (navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1) { // detect Safari for fullscreen caption support
        Safari = true;
    }

    //Hide tracks not provided by mediahub (safari will list tracks that are muxed into the mp4 file itself).
    t.on('loadeddata', function() {
        for (var i = 0; i < t.textTracks().length; i++) {
            var track = t.textTracks()[i];
            if (track.id.lastIndexOf('mediahub', 0) !== 0) {
                //Prevent non-mediahub tracks from showing up
                t.textTracks().removeTrack(t.textTracks()[i]);
            }
        }
    });
    var captionsFound = false;
    t.textTracks().on("addtrack", function(e) {
        if (e.track.kind === 'metadata') {
            //Ignore metadata tracks
            return;
        }
        
        if (captionsFound) {
            //Already added a button, no need to add it again.
            return;
        }
        
        if (t.textTracks().length !== 0) {
            captionsFound = true;
            var $container;
            var $myButton = $('<button>', {
                "class": "vjs-control vjs-button search-caption caption-toggle",
                "type": "button",
                "aria-controls": t.id,
                "aria-label": "Toggle Searchable Transcript"
            });

            if (isEmbed) {
                //Try to show the transcript in the embed
                $container = $(t.el());
                $container.append($(".mh_transcript_template").html());
                $(t.controlBar.subsCapsButton.el()).before($myButton);
            } else {
                //Use the transcript that is already on the mediahub page
                $container = $(window.parent.document).find(".mediahub-onpage-captions");
                $container.html($(".mh_transcript_template").html());
            }

            $transcript = $container.find('.mh-transcript');
            $captionSearch = $container.children(".mh-caption-search");
            $mhLanguageSelect = $container.find("#mh-language-select");

            if (!Safari) {
                $(t.controlBar.el()).on("click", ".caption-toggle", function(e) {
                    $captionSearch.toggleClass("show");
                });
                $captionSearch.on("click", ".caption-toggle", function(e) {
                    $captionSearch.toggleClass("show");
                });
            } else { // exit fullscreen if searchable captions are opened in safari. 
                $(t.controlBar.el()).on("click", ".caption-toggle", function(e) {
                    $captionSearch.toggleClass("show");
                    if ($captionSearch.hasClass("full-screen") && $captionSearch.hasClass("show")) {
                        t.exitFullScreen();
                    }
                });
                $captionSearch.on("click", ".caption-toggle", function(e) {
                    $captionSearch.toggleClass("show");
                    if ($captionSearch.hasClass("full-screen") && $captionSearch.hasClass("show")) {
                        t.exitFullScreen();
                    }
                });
            }

            var displayTime = function(millis) {
                var hours = Math.floor(millis / 36e5),
                    mins = Math.floor((millis % 36e5) / 6e4),
                    secs = Math.floor((millis % 6e4) / 1000);
                if (secs < 10) {
                    secs = "0" + secs;
                }
                if (hours > 0) {
                    return hours + ':' + mins + ':' + secs;
                } else {
                    return mins + ':' + secs;
                }
            };

            var setTranscript = function(track) {
                if ((track.cues === null) || (track.cues === undefined) || (track.cues.length === 0)) { // If the track has no cues wait a quarter second and try again. this seems like a dumb way to do this. 🙄
                    setTimeout(function() {
                        setTranscript(track);
                    }, 250);
                    return;
                }

                $captionSearch.find(".mh-caption-close").on("click", function() {
                    $(this).siblings("#mh-parse-caption").val("");
                    $transcript.find("a").addClass("highlight");
                });

                $captionSearch.find("#mh-parse-caption").on("keydown keyup focus blur", function(e) {
                    e.stopPropagation();
                    var search = $(this).val().toLowerCase();
                    var subtitlesLength;
                    var i;

                    subtitlesLength = track.cues.length;
                    for (i = 0; i < subtitlesLength; i++) {
                        var line = track.cues[i].text.toLowerCase();
                        if (line.indexOf(search) > -1) {
                            $transcript.find("a").eq(i).addClass("highlight");
                        } else {
                            $transcript.find("a").eq(i).removeClass("highlight");
                        }
                    }
                    if (isEmbed) {
                        $transcript.scrollTo(".highlight", 100);
                    }
                });

                $container.find(".mh-paragraph-icons").off();
                $container.find(".mh-paragraph-icons").on("click", function() {
                    $container.find(".mh-caption-search").toggleClass("bulleted");

                });

                $transcript.on('click', 'a', function() {
                    var time;
                    time = $(this).data('timeOffset')
                    if (!time) {
                        return;
                    }
                    if (!isEmbed) {
                        $parentDocument.find("html, body").animate({ scrollTop: $parentDocument.find(".mh-video-band").offset().top - 50 }, 100, function() {
                            var promise = t.play();

                            if (promise !== undefined) {
                                promise.catch(error => {
                                    // Not able to play video, so mute it and then play
                                    t.muted(true);
                                    t.play();
                                }).then(() => {
                                    // Auto-play started
                                });
                            }
                        });
                    }
                    t.currentTime(time);
                });
                var listItems = [];
                for (var i = 0; i < track.cues.length; i++) {

                    listItems.push($("<li>").append($('<a>', {
                            "class": "highlight",
                            "href": "javascript:void(0);"
                            // "tabindex" : 0,
                        })
                            .data('timeOffset', track.cues[i].startTime)
                            .text($($.parseHTML(track.cues[i].text)).text())
                            .prepend($('<span>').text('[' + displayTime(track.cues[i].startTime * 1000) + '] '))
                    ));
                }
                $transcript.children("li").remove();
                $transcript.append(listItems);
            };

            t.on("fullscreenchange", function() {
                if (!t.isFullscreen()) {
                    $container.find(".mh-caption-search").removeClass("full-screen");
                } else {
                    $container.find(".mh-caption-search").addClass("full-screen");
                    if (Safari) { // remove searchable captions if entering full screen on safari
                        $captionSearch.removeClass("show");
                    }
                }
            });
            var textTracks = t.textTracks();
            var defaultCaptions;
            for (var i = 0; i < textTracks.length; i++) {
                if (textTracks[i].language = 'en' && textTracks[i].type !== 'metadata') {
                    defaultCaptions = textTracks[i];

                    if (defaultCaptions.mode === "disabled" && !defaultCaptions.cues) {
                        // Try to load the cues but don't force them to show.
                        // Some browsers (Safari) won't load the cues if the track is 'disabled'
                        defaultCaptions.mode = "hidden";
                    }
                }
            }

            // When there are no textTracks that are language 'en' try to find one that starts with en
            // If you still can't find any then just use the first one
            if (defaultCaptions === undefined && textTracks.length > 0) {
                for (var i = 0; i < textTracks.length; i++) {
                    if (textTracks[i].language.startsWith('en') && textTracks[i].type !== 'metadata') {
                        defaultCaptions = textTracks[i];

                        if (defaultCaptions.mode === "disabled" && !defaultCaptions.cues) {
                            // Try to load the cues but don't force them to show.
                            // Some browsers (Safari) won't load the cues if the track is 'disabled'
                            defaultCaptions.mode = "hidden";
                        }
                    }
                }

                if (defaultCaptions === undefined) {
                    defaultCaptions = textTracks[1];
                }
            }

            if (textTracks.length > 0) {
                textTracks.on("change", function(e) {
                    for (var i = 0; i < textTracks.length; i++) {
                        if (textTracks[i].mode === "showing") {
                            setTranscript(textTracks[i])
                        }
                    }
                });
            }

            // whether to display captions by default
            if (textTracks.length > 0 && captions) {
                defaultCaptions.mode = "showing";
            }
            setTranscript(defaultCaptions);

            $mhLanguageSelect.on("change", function() {
                var value = $(this).find(":selected").val();
                t.setTrack(value);
            });
        }
    });

    var w = false;
    var playEvent = function() {
        if (!w) {
            $.post(options.url, { action: "playcount" });
            w = true;
        }

        // if audio with poster, clear poster on play
        if (t.isAudio() && t.poster()) {
            t.poster('');
        }

        var message = {
            'message_type': 'ga_event',
            'event': 'play',
            'media_id': mediahub_id,
            'media_title': $video.attr('title'),
            'url': options.url,
            'type': v.tagName.toString().toLowerCase()
        };
        window.parent.postMessage(message, "*");
    };
    
    var pauseEvent = function() {
        var message = {
            'message_type': 'ga_event',
            'event': 'pause',
            'media_id': mediahub_id,
            'media_title': $video.attr('title'),
            'url': options.url,
            'type': v.tagName.toString().toLowerCase()
        };
        window.parent.postMessage(message, "*");
    };

    // Wave surfer doesn't do a good job of emitting standard events
    // So we have to do some hackery to get it to work right
    t.on('ready', function() {
        if (t.wavesurfer) {
            t.wavesurfer().surfer.on('play', playEvent);
            t.wavesurfer().surfer.on('pause', pauseEvent);
        } else {
            t.on('play', playEvent);
            t.on('pause', pauseEvent);
        }
    });

    // This works fine for both audio and video
    t.on('ended', function() {
        var message = {
            'message_type': 'ga_event',
            'event': 'ended',
            'media_id': mediahub_id,
            'media_title': $video.attr('title'),
            'url': options.url,
            'type': v.tagName.toString().toLowerCase()
        };
        window.parent.postMessage(message, "*");
    });

    t.on("ready", function() {
        if (t.isAudio()) {
            //We are using wavesurfer. Add some accessible forward and back buttons.

            var $skipForward = $('<button>', {
                "class": "vjs-control vjs-button fa fa-fast-forward",
                "type": "button",
                "aria-controls": t.id,
                "aria-label": "skip forward"
            });
            $skipForward.on('click', function() {
                t.wavesurfer().surfer.skipForward(10);
            });
            $(t.controlBar.durationDisplay.el()).after($skipForward);

            var $skipBackward = $('<button>', {
                "class": "vjs-control vjs-button fa fa-fast-backward",
                "type": "button",
                "aria-controls": t.id,
                "aria-label": "skip backward"
            });
            $skipBackward.on('click', function() {
                t.wavesurfer().surfer.skipBackward(10);
            });
            $(t.controlBar.durationDisplay.el()).after($skipBackward);
        }
        
        //Fix for preload=none having an endless loading gif
        if ($video.attr('preload') === 'none') {
            $video.parents('.mejs-container').find('.mejs-overlay-loading').hide();
        }

        //Social Sharing via https://xparkmedia.com/blog/mediaelements-add-a-share-button-to-video-elements-using-jquery/
        var initSharing = function(t, v) {
            var $title = $video.attr('title');
            $title = $("<div>").text($title).html(); //escape it
            
            var share_url = $video.attr('data-url');
            var media_type = 'video';
            if (t.isAudio()) {
                media_type = 'audio';
            }
            
            if (!share_url) {
                return;
            }
            
            // share urls
            var sharelinks = {
                "mh-icon-mail": { title: 'Email', url: 'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ': ' + $title },
                "mh-icon-facebook": { title: 'Facebook', url: 'https://www.facebook.com/sharer/sharer.php?u=' + share_url }, // facebook
                "mh-icon-twitter": { title: 'Twitter', url: 'https://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url }, // twitter
                "mh-icon-linkedin-squared": { title: 'LinkedIn', url: 'https://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title=' + $title + '&summary=Checkout this ' + media_type + '%20&source=University%20of%20Nebraska%20-%20Lincoln%20MediaHub' } //google plus
            };

            //create share links
            var links = '<li><a href="https://go.unl.edu/?url=referer"  target="_parent" rel="nofollow"><span class="mh-icon-link" aria-hidden="true"></span>Get a Go URL</a></li>';
            for (var key in sharelinks) {
                links += '<li class="outpost"><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"><span class="' + key + '" aria-hidden="true"></span> Share on ' + sharelinks[key].title + '</a></li>';
            }

            var html = '<div class="media-content-head mejs-control">';
            html += '<div class="media-content-title"><a href="' + share_url + '" target="_parent">' + $title + '</a></div>';
            html += '<div class="mh-share-this-page mejs-control">';
            html += '<button class="share-this-media"><span  class="mh-icon-share" aria-hidden="true"></span><span class="mh-text-hidden">Share This</span></button>';
            html += '<ul class="mh-share-options vjs-hidden">';
            html += links;
            html += '</ul>';
            html += '</div>';
            html += '</div>';

            $video.prepend(html);
            
            $('button.share-this-media', $video).click(function() {
                var $container = $('.mh-share-options', $video).first();
                $container.toggleClass('vjs-hidden');
                if (!$container.hasClass('vjs-hidden')) {
                    //We showed it, so send focus to the container
                    $('a', $container).first().focus();
                }
            });

            if (window.location.search.indexOf("hide-content-header=1") > -1) { // add ability to hide video titles. 
                $video.find(".media-content-head").addClass("vjs-hidden");
            }
        };

        if (options.privacy === "PUBLIC") {
            initSharing(t, v);
        }
    });
}

function canAccessParent() {
    var sameOrigin;
    try {
        sameOrigin = window.parent.location.host === window.location.host;
    } catch (e) {
        sameOrigin = false;
    }
    return sameOrigin;
}

jQuery.fn.scrollTo = function(elem, speed) { 
    $(this).animate({
        scrollTop:  $(this).scrollTop() - $(this).offset().top + $(elem).offset().top - 13
    }, speed === undefined ? 1000 : speed); 
    return this; 
};
