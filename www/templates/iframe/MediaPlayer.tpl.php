<div class="mediahub-embed non-framework">
    <?php
    if ($context->media->isVideo()) {
        echo $savvy->render($context->media, 'MediaPlayer/Video.tpl.php');
    } else {
        echo $savvy->render($context->media, 'MediaPlayer/Audio.tpl.php');
    }
    
    $getTracks = $context->media->getTextTrackURLs();
    
    ?>
</div>

<?php if($getTracks){
    echo '<script type="htmltemplate" class="mh_transcript_template">';
        echo $savvy->render($context->media, 'MediaPlayer/Transcript.tpl.php');
    echo '</script>';
    }
?>


<script type="text/javascript">
    (function () {
        var e = function () {

            function canAccessParent() {
                var sameOrigin;
                try
                {
                    sameOrigin = window.parent.location.host == window.location.host;
                }
                catch (e)
                {
                    sameOrigin = false;
                }
                return sameOrigin;
            }

            var isEmbed = !canAccessParent();
            if(!isEmbed){
                $parentDocument = $(window.parent.document);
            }

            <?php if (isset($context->media->id) && $context->media->id) { ?>
            var options = {
                videoWidth: '100%',
                //setDimensions: false,
                videoHeight: '100%',
                audioWidth: '100%',
                toggleCaptionsButtonWhenOnlyOne: true,
                features : [
                    'playpause',
                    'current',
                    'progress',
                    'duration',
                    'tracks',
                    'volume',
                    'fullscreen',
                    'googleanalytics'
                ],
                success: function (m, v, t) {
                    //var $ = WDN.jQuery;
                    var $transcript;
                    var $captionSearch;
                    var $video = $(v);
                    var mediahub_id = $video.attr('data-mediahub-id');
                    var $mhLanguageSelect;

                    var Safari = false;
                    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) { // detect Safari for fullscreen caption support
                        Safari = true;
                    }

                    if(t.captionsButton){

                        var $container;
                        var $myButton = $('<div>', {
                            "class": "mejs-button wide"
                        }).append($('<button>', {
                            "class": "wdn-icon-search caption-toggle",
                            "type": "button",
                            "aria-controls": t.id,
                            "title": "Toggle Searchable Transcript",
                            "aria-label": "Toggle Searchable Transcript"
                        }));

                        if(isEmbed){
                            $container = t.container;
                            $container.append($(".mh_transcript_template").html());
                            t.captionsButton.before($myButton)
                        }else{
                            $container = $(window.parent.document).find(".mediahub-onpage-captions");
                            $container.html($(".mh_transcript_template").html());
                        }

                        $transcript = $container.find('.mh-transcript');
                        $captionSearch = $container.children(".mh-caption-search");
                        $mhLanguageSelect = $container.find("#mh-language-select");

                        if (!Safari) {
                            t.controls.on("click", ".caption-toggle", function(e){
                                $captionSearch.toggleClass("show");
                            });
                            $captionSearch.on("click", ".caption-toggle", function(e){
                                $captionSearch.toggleClass("show");
                            });
                        } else { // exit fullscreen if searchable captions are opened in safari. 
                            t.controls.on("click", ".caption-toggle", function(e){
                                $captionSearch.toggleClass("show");
                                if ($captionSearch.hasClass("full-screen") && $captionSearch.hasClass("show")) {
                                    t.exitFullScreen();
                                }
                            });
                            $captionSearch.on("click", ".caption-toggle", function(e){
                                $captionSearch.toggleClass("show");
                                if ($captionSearch.hasClass("full-screen") && $captionSearch.hasClass("show")) {
                                    t.exitFullScreen();
                                }
                            });
                        };

                        var displaytime = function(millis){
                            var hours = Math.floor(millis / 36e5),
                                mins = Math.floor((millis % 36e5) / 6e4),
                                secs = Math.floor((millis % 6e4) / 1000);
                            if(secs < 10){
                                secs = "0"+secs;
                            };
                            if(hours > 0){
                                return hours+':'+mins+':'+secs;
                            }else{
                                return mins+':'+secs;
                            };
                        }

                        var setTranscript = function(track){
                            $captionSearch.find(".mh-caption-close").on("click", function(){
                                $(this).siblings("#mh-parse-caption").val("");
                                $transcript.find("a").addClass("highlight");
                            });

                            $captionSearch.find("#mh-parse-caption").on("keydown keyup focus blur", function(e){
                                e.stopPropagation();
                                var search = $(this).val().toLowerCase();
                                var subtitlesLength;
                                var i;

                                subtitlesLength = track.entries.text.length;
                                for (i = 0; i < subtitlesLength; i++) {
                                    var line = track.entries.text[i].toLowerCase();
                                    if (line.indexOf(search) > -1){
                                        $transcript.find("a").eq(i).addClass("highlight");
                                    }else{
                                        $transcript.find("a").eq(i).removeClass("highlight");
                                    }
                                };
                                if(isEmbed){
                                    $transcript.scrollTo(".highlight", 100);
                                }
                            });

                            $container.find(".mh-paragraph-icons").off();
                            $container.find(".mh-paragraph-icons").on("click", function(){
                                $container.find(".mh-caption-search").toggleClass("bulleted");

                            });

                            $transcript.on('click', 'a', function() {
                                var time;
                                time = $(this).data('timeOffset')
                                if (!time) {
                                    return;
                                }
                                if(!isEmbed){
                                    $parentDocument.find("html, body").animate({scrollTop: $parentDocument.find(".mh-video-band").offset().top-50}, 100, function(){
                                        t.play();
                                    });
                                }
                                t.setCurrentTime(time);
                            });
                            var listItems = [];
                            for (var i = 0; i < track.entries.text.length; i++) {
                                listItems.push($("<li>").append($('<a>',  {
                                        "class" : "highlight",
                                        "href" : "javascript:void(0);"
                                        // "tabindex" : 0,
                                    })
                                    .data('timeOffset', track.entries.times[i].start)
                                    .text($($.parseHTML(track.entries.text[i])).text())
                                    .prepend($('<span>').text('[' + displaytime(track.entries.times[i].start*1000) + '] '))
                                ));
                            };
                            $transcript.children("li").remove();
                            $transcript.append(listItems);
                        };

                        var origsenterFullScreen = t.enterFullScreen;
                        t.enterFullScreen = function() {
                            origsenterFullScreen.call(this);
                            $container.find(".mh-caption-search").addClass("full-screen");
                            if (Safari) { // remove searchable captions if entering full screen on safari
                                $captionSearch.removeClass("show");
                            };
                        };

                        var origsexitFullScreen = t.exitFullScreen;
                        t.exitFullScreen = function() {
                            origsexitFullScreen.call(this);
                            $container.find(".mh-caption-search").removeClass("full-screen");
                        };

                        var origsEnableTrackButton = t.enableTrackButton;
                        t.enableTrackButton = function(lang, label) {
                            origsEnableTrackButton.call(this, lang, label);
                            var t = this;
                            setTranscript(t.tracks[0]);
                            t.enableTrackButton = origsEnableTrackButton;
                        };

                        var origsSetTrack = t.setTrack;
                        t.setTrack = function(lang) {
                            origsSetTrack.call(this, lang);
                            var t = this,
                                i;
                            if (lang != 'none') {
                                setTranscript(t.selectedTrack);
                            };
                        };

                        $mhLanguageSelect.on("change", function(){
                            var value = $(this).find(":selected").val();
                            t.setTrack(value);
                        });

                    };

                    // Playcount
                    var w = false, u = '<?php echo UNL_MediaHub_Controller::toAgnosticURL($controller->getURL($context->media)) ?>';
                    m.addEventListener('play', function () {
                        if (!w) {
                            $.post(u, {action: "playcount"});
                            w = true;
                        }
                    });

                    //Fix for preload=none having an endless loading gif
                    if ($video.attr('preload') == 'none') {
                        $video.parents('.mejs-container').find('.mejs-overlay-loading').hide();
                    }

                    //Social Sharing via https://xparkmedia.com/blog/mediaelements-add-a-share-button-to-video-elements-using-jquery/
                    var initSharing = function(m, v) {
                        var $inner      = false;
                        var $title      = $video.attr('title');
                        var share_url   = $video.attr('data-url');
                        var media_type  = v.tagName.charAt(0).toUpperCase() + v.tagName.slice(1).toLowerCase();


                        if (!share_url) {
                            return;
                        }

                        if ($inner = $video.parents('.mejs-container')) {
                            // share urls
                            var sharelinks = {
                                "wdn-icon-mail":     {title: 'Email', url:'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ' : ' + $title},
                                "wdn-icon-facebook":     {title: 'Facebook', url:'https://www.facebook.com/sharer/sharer.php?u=' + share_url},  // facebook
                                "wdn-icon-twitter":     {title: 'Twitter', url:'https://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url}, // twitter
                                "wdn-icon-linkedin-squared":     {title: 'LinkedIn', url:'https://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title='+ $title +'&summary=Checkout this '+ media_type +'%20&source=University%20of%20Nebraska%20-%20Lincoln%20MediaHub'} //google plus
                            }

                            //create share links
                            var links = '<li><a href="https://go.unl.edu/?url=referer" class="wdn-icon-link" rel="nofollow">Get a Go URL</a></li>';
                            for (var key in sharelinks) {
                                links += '<li class="outpost"><a href="'+sharelinks[key].url+'" rel="nofollow" target="_blank" class="'+key+'" title="Share on '+sharelinks[key].title+'">Share on '+sharelinks[key].title+'</a></li>';
                            }

                            var html = '<div class="media-content-head mejs-control">';
                            html += '<div class="media-content-title"><a href="' + share_url + '" target="_parent">' + $title + '</a></div>';
                            html += '<div class="wdn-share-this-page mejs-control">';
                            html += '<input type="checkbox" id="mh-share-toggle'+mediahub_id+'" value="Show share options" class="wdn-input-driver mh-share-toggle">'
                            html += '<label for="mh-share-toggle'+mediahub_id+'" class="wdn-icon-share">Share This Page</label>';
                            html += '<ul class="wdn-share-options">';
                            html += links;
                            html += '</ul>';
                            html += '</div>';
                            html += '</div>';


                            $inner.prepend(html);

                            // start listeners
                            var $sharelink  = $inner.find('.share-video-link');
                            var $sharefrom  = $inner.find('.share-video-form');
                            var $closelink  = $inner.find('.share-video-close');
                            var $videotitle = $inner.find('.media-content-title');
                            var $videohead  = $inner.find('.media-content-head');

                            if(t.media.getAttribute('autoplay') != null){
                                $videohead.hide();
                                //hide title if autoplay is enabled
                            };

                            // hide form when video is playing
                            m.addEventListener('play', function(e) {
                                //$sharelink.hide();  $videotitle.hide();
                                $videohead.hide();
                                $sharefrom.hide();
                            }, false );

                            // show form when video is paused
                            m.addEventListener('pause', function(e) {
                                $sharelink.removeClass('video-active');
                                $inner.find('.mejs-overlay-button').show();
                                $videohead.show();
                            }, false );

                            // close video form
                            var video_close_share_form = function($inner, $form) {
                                $form.hide();
                                $form.removeClass('video-active');

                                $inner.find('.mejs-overlay-play')
                                    .removeClass('share-overlay');
                            };
                            var video_open_share_form = function($inner, $form) {
                                $form.show();
                                $(this).addClass('video-active');

                                $inner.find('.mejs-overlay-play')
                                    .addClass('share-overlay').show();
                            }
                            var toggle_share_form = function($inner) {
                                var $form = $inner.find('.share-video-form');

                                if($form.is(':hidden')) {
                                    video_open_share_form($inner, $form);
                                } else {
                                    video_close_share_form($inner, $form);
                                }
                            }

                            // show / hide video form
                            $closelink.bind('click', function(e) {
                                e.preventDefault();

                                var $inner = $(this).parents('.mejs-container');
                                var $form = $inner.find('.share-video-form');
                                video_close_share_form($inner, $form);
                            });

                            $sharelink.bind('click', function(e){
                                e.preventDefault();

                                toggle_share_form($(this).parents('.mejs-container'));
                            });
                        }
                    };
                    
                    <?php if($context->media->privacy === "PUBLIC"): ?>
                    initSharing(m, v);
                    <?php endif; ?>
                }
            };
            
            <?php } ?>

            $(function() {
                $('video, audio').each(function() {
                    $(this).mediaelementplayer(options);
                });
            });
        };
        e();       
    })();

    jQuery.fn.scrollTo = function(elem, speed) { 
        $(this).animate({
            scrollTop:  $(this).scrollTop() - $(this).offset().top + $(elem).offset().top - 13
        }, speed == undefined ? 1000 : speed); 
        return this; 
    };

</script>
