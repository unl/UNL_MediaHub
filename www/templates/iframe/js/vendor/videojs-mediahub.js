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
                "mail-share-link-data": { title: 'Email', url: 'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ': ' + $title },
                "facebook-share-link-data": { title: 'Facebook', url: 'https://www.facebook.com/sharer/sharer.php?u=' + share_url }, // facebook
                "x-share-link-data": { title: 'X', url: 'https://x.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url }, // X, formerly known as twitter
                "linkedin-share-link-data": { title: 'LinkedIn', url: 'https://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title=' + $title + '&summary=Checkout this ' + media_type + '%20&source=University%20of%20Nebraska%20-%20Lincoln%20MediaHub' } //linkedin
            };

            //create share links
            var links = '<li><svg style="fill: currentColor;"  focusable="false" height="16" width="16" viewbox="0 0 24 24" aria-hidden="true"><path d="m14.474 10.232-.706-.706a4.004 4.004 0 0 0-5.658-.001l-4.597 4.597a4.004 4.004 0 0 0 0 5.657l.707.706a3.97 3.97 0 0 0 2.829 1.173 3.973 3.973 0 0 0 2.827-1.172l2.173-2.171a.999.999 0 1 0-1.414-1.414l-2.173 2.17c-.755.756-2.071.757-2.828 0l-.707-.706a2.004 2.004 0 0 1 0-2.829l4.597-4.596c.756-.756 2.073-.756 2.828 0l.707.707a1.001 1.001 0 0 0 1.415-1.415z"/><path d="m20.486 4.221-.707-.706a3.97 3.97 0 0 0-2.829-1.173 3.977 3.977 0 0 0-2.827 1.172L12.135 5.5a.999.999 0 1 0 1.414 1.414l1.988-1.984c.755-.756 2.071-.757 2.828 0l.707.706c.779.78.779 2.049 0 2.829l-4.597 4.596c-.756.756-2.073.756-2.828 0a.999.999 0 0 0-1.414 0 .999.999 0 0 0-.001 1.414 4.001 4.001 0 0 0 5.657.001l4.597-4.597a4.005 4.005 0 0 0 0-5.658z"/><path fill="none" d="M0 0h24v24H0z"/></svg> <a href="https://go.unl.edu/?url=referer"  target="_parent" rel="nofollow">Get a Go URL</a></li>'
            for (var key in sharelinks) {
                if (key == 'x-share-link-data') {
                links += '<li class="outpost"><svg style="fill: currentColor; vertical-align: middle;" focusable="false" height="16" width="16" viewBox="0 0 48 48"><title>X</title><path d="M28.5 20.3 46 0h-4.1L26.6 17.6 14.5 0H.5l18.3 26.7L.5 48h4.1l16-18.6L33.5 48h14l-19-27.7zm-5.7 6.6-1.9-2.7L6.2 3.1h6.4l11.9 17.1 1.9 2.7L41.8 45h-6.4L22.8 26.9z"></path></svg><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"> Share on ' + sharelinks[key].title + '</a></li>'
                } else if (key == 'facebook-share-link-data') {
                links += '<li class="outpost"><svg style="fill: currentColor; vertical-align: middle;" focusable="false" height="16" width="16" viewbox="0 0 48 48"><path d="M48 24.1c0-13.3-10.7-24-24-24S0 10.9 0 24.1c0 12 8.8 21.9 20.2 23.7V31.1h-6.1v-6.9h6.1v-5.3c0-6 3.6-9.3 9.1-9.3 2.6 0 5.4.5 5.4.5V16h-3c-3 0-3.9 1.9-3.9 3.7v4.5h6.7l-1.1 6.9h-5.6v16.8C39.2 46.1 48 36.1 48 24.1z"></path></svg><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"> Share on ' + sharelinks[key].title + '</a></li>'} 
                else if (key == 'linkedin-share-link-data') {
                links += '<li class="outpost"><svg style="fill: currentColor; vertical-align: middle;" focusable="false" height="16" width="16" viewbox="0 0 48 48"><path d="M44.45 0H3.54A3.5 3.5 0 0 0 0 3.46v41.08A3.5 3.5 0 0 0 3.54 48h40.91A3.51 3.51 0 0 0 48 44.54V3.46A3.51 3.51 0 0 0 44.45 0zM14.24 40.9H7.11V18h7.13zm-3.56-26a4.13 4.13 0 1 1 4.13-4.13 4.13 4.13 0 0 1-4.13 4.1zm30.23 26h-7.12V29.76c0-2.66 0-6.07-3.7-6.07s-4.27 2.9-4.27 5.88V40.9h-7.11V18h6.82v3.13h.1a7.48 7.48 0 0 1 6.74-3.7c7.21 0 8.54 4.74 8.54 10.91z"></path></svg><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"> Share on ' + sharelinks[key].title + '</a></li>'
                } else if (key == 'mail-share-link-data') {
                links += '<li class="outpost"><svg style="fill: currentColor; vertical-align: middle;" focusable="false" height="16" width="16" viewbox="0 0 24 24" aria-hidden="true"><path d="m12.002 12.36 10.095-8.03A1.99 1.99 0 0 0 21.001 4h-18c-.387 0-.746.115-1.053.307l10.054 8.053z"/><path d="m22.764 5.076-10.468 8.315a.488.488 0 0 1-.594-.001L1.26 5.036c-.16.287-.259.612-.26.964v11c.001 1.103.898 2 2.001 2h17.998c1.103 0 2-.897 2.001-2V6c0-.335-.09-.647-.236-.924z"/><path fill="none" d="M0 0h24v24H0z"/></svg><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"> Share on ' + sharelinks[key].title + '</a></li>'
                }
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
