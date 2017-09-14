videojs.registerPlugin('MediahubPlayer', MediahubPlayer);


function MediahubPlayer(options) {
    options = (typeof options !== 'undefined') ? options : {};

    this.on("ready", function() {

        var isEmbed = !canAccessParent();
        if (!isEmbed) {
            $parentDocument = $(window.parent.document);
        }

        var t = this;
        var v = this.el();

        var $transcript;
        var $captionSearch;
        var $video = $(v);
        var mediahub_id = $video.attr('data-mediahub-id');
        var $mhLanguageSelect;

        var Safari = false;
        if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) { // detect Safari for fullscreen caption support
            Safari = true;
        }

        //Hide tracks not provided by mediahub
        t.on('loadedmetadata', function() {
            for (var i = 0; i < t.textTracks().length; i++) {
                var track = t.textTracks()[i];
                if (track.id.lastIndexOf('mediahub', 0) !== 0) {
                    //Prevent non-mediahub tracks from showing up
                    t.textTracks()[i].kind = "metadata";
                }
            }
        });

        var preventedDoubleCaptions = false;
        if (Safari) {
            //A track has not yet been selected by mediaelement
            //So this is probably safari forcing captions.
            //Disable this to prevent double captions
            //This will also result in captions not being played by default in iOS
            t.on('loadedmetadata', function() {
                t.textTracks().onchange = function(e) {
                    if (false == preventedDoubleCaptions && t.textTracks().length) {
                        for (var i = 0; i < t.textTracks().length; i++) {
                            t.textTracks()[i].mode = "disabled";
                        }

                        //Support the default selected track as set by mediahub
                        if (t.selectedTrack) {
                            t.setTrack(t.selectedTrack);
                        }

                        preventedDoubleCaptions = true;
                    }
                };
            });
        }

        t.on("loadedmetadata", function() {
            if (t.textTracks().length != 0) {

                var $container;
                var $myButton = $('<button>', {
                    "class": "vjs-control vjs-button search-caption caption-toggle",
                    "type": "button",
                    "aria-controls": t.id,
                    "title": "Toggle Searchable Transcript",
                    "aria-label": "Toggle Searchable Transcript"
                });

                if (isEmbed) {
                    $container = $(t.el());
                    $container.append($(".mh_transcript_template").html());
                    $(t.controlBar.captionsButton.el()).before($myButton)
                } else {
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
                };

                var displaytime = function(millis) {
                    var hours = Math.floor(millis / 36e5),
                        mins = Math.floor((millis % 36e5) / 6e4),
                        secs = Math.floor((millis % 6e4) / 1000);
                    if (secs < 10) {
                        secs = "0" + secs;
                    };
                    if (hours > 0) {
                        return hours + ':' + mins + ':' + secs;
                    } else {
                        return mins + ':' + secs;
                    };
                }

                var setTranscript = function(track) {

                    if ((track.cues == null) || (track.cues == undefined) || (track.cues.length == 0)) { // If the track has no cues wait a quarter second and try again. this seems like a dumb way to do this. 🙄
                        setTimeout(function() {
                            console.log("yeah?")
                            setTranscript(track);
                        }, 250)
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
                        };
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
                                t.play();
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
                            .prepend($('<span>').text('[' + displaytime(track.cues[i].startTime * 1000) + '] '))
                        ));
                    };
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
                        };
                    }
                })
                var textTracks = t.textTracks()
                var defaultCaptions = 0;
                for (var i = 0; i < textTracks.length; i++) {
                    if (textTracks[i].language == "en") {
                        defaultCaptions = i;
                    }
                }
                if (textTracks.length > 0) {
                    textTracks.on("change", function(e) {
                        for (var i = 0; i < textTracks.length; i++) {
                            if (textTracks[i].mode == "showing") {
                                setTranscript(textTracks[i])
                            }
                        }
                    });
                }
                // textTracks[defaultCaptions].mode = "showing";
                setTranscript(textTracks[defaultCaptions])

                $mhLanguageSelect.on("change", function() {
                    var value = $(this).find(":selected").val();
                    t.setTrack(value);
                });



            };

        })


        // Playcount
        var w = false;
        t.on('play', function() {
            if (!w) {
                $.post(options.url, { action: "playcount" });
                w = true;
            }
        });

        //Fix for preload=none having an endless loading gif
        if ($video.attr('preload') == 'none') {
            $video.parents('.mejs-container').find('.mejs-overlay-loading').hide();
        }

        //Social Sharing via https://xparkmedia.com/blog/mediaelements-add-a-share-button-to-video-elements-using-jquery/
        var initSharing = function(t, v) {
            var $title = $video.attr('title');
            var share_url = $video.attr('data-url');
            var media_type = v.tagName.charAt(0).toUpperCase() + v.tagName.slice(1).toLowerCase();


            if (!share_url) {
                return;
            }
            
            // share urls
            var sharelinks = {
                "wdn-icon-mail": { title: 'Email', url: 'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ' : ' + $title },
                "wdn-icon-facebook": { title: 'Facebook', url: 'https://www.facebook.com/sharer/sharer.php?u=' + share_url }, // facebook
                "wdn-icon-twitter": { title: 'Twitter', url: 'https://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url }, // twitter
                "wdn-icon-linkedin-squared": { title: 'LinkedIn', url: 'https://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title=' + $title + '&summary=Checkout this ' + media_type + '%20&source=University%20of%20Nebraska%20-%20Lincoln%20MediaHub' } //google plus
            }

            //create share links
            var links = '<li><a href="https://go.unl.edu/?url=referer"  target="_parent" rel="nofollow"><span class="wdn-icon-link" aria-hidden="true"></span>Get a Go URL</a></li>';
            for (var key in sharelinks) {
                links += '<li class="outpost"><a href="' + sharelinks[key].url + '" rel="nofollow" target="_blank"><span class="' + key + '" aria-hidden="true"></span> Share on ' + sharelinks[key].title + '</a></li>';
            }

            var html = '<div class="media-content-head mejs-control">';
            html += '<div class="media-content-title"><a href="' + share_url + '" target="_parent">' + $title + '</a></div>';
            html += '<div class="wdn-share-this-page mejs-control">';
            html += '<input type="checkbox" id="mh-share-toggle' + mediahub_id + '" value="Show share options" class="wdn-input-driver mh-share-toggle">'
            html += '<label for="mh-share-toggle' + mediahub_id + '"><span  class="wdn-icon-share" aria-hidden="true"></span><span class="wdn-text-hidden">Share This Page</span></label>';
            html += '<ul class="wdn-share-options">';
            html += links;
            html += '</ul>';
            html += '</div>';
            html += '</div>';

            $video.prepend(html);

            if (window.location.search.indexOf("hide-content-header=1") > -1) { // add ability to hide video titles. 
                $video.find(".media-content-head").addClass("vjs-hidden")
            }
        };

        t.on('play', function() {
            var message = {
                'message_type': 'ga_event',
                'event': 'play',
                'media_id': mediahub_id,
                'media_title': $video.attr('title'),
                'url': options.url,
                'type': v.tagName.toString().toLowerCase()
            };
            window.parent.postMessage(message, "*");
        });

        t.on('pause', function() {
            var message = {
                'message_type': 'ga_event',
                'event': 'pause',
                'media_id': mediahub_id,
                'media_title': $video.attr('title'),
                'url': options.url,
                'type': v.tagName.toString().toLowerCase()
            };
            window.parent.postMessage(message, "*");
        });

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

        if (options.privacy === "PUBLIC") {
            initSharing(t, v);
        }

    })

}


function canAccessParent() {
    var sameOrigin;
    try {
        sameOrigin = window.parent.location.host == window.location.host;
    } catch (e) {
        sameOrigin = false;
    }
    return sameOrigin;
}

jQuery.fn.scrollTo = function(elem, speed) { 
    $(this).animate({
        scrollTop:  $(this).scrollTop() - $(this).offset().top + $(elem).offset().top - 13
    }, speed == undefined ? 1000 : speed); 
    return this; 
};