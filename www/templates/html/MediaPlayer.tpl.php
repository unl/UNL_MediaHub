<?php
if ($context->media->isVideo()) {
    echo $savvy->render($context->media, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context->media, 'MediaPlayer/Audio.tpl.php');
}

$jsonTrack = @file_get_contents($context->media->getVideoTextTrackURL("json")); 

?>

<?php if($jsonTrack): ?>
    <div class="wdn-grid-set">
        <div class="wdn-col-full mh-caption-search">
            <h6 class="wdn-sans-serif wdn-icon-search">
                Searchable Transcript
                <div class="wdn-icon-info mh-tooltip hang-right italic" id="privacy-details">
                    <div>
                        <ul>
                            <li>Click header to toggle. </li>
                            <li>Use the text input to search the transcript. </li>
                            <li>Click any line to jump to that spot in the video. </li>
                            <li>Use the icons to the right to toggle between list and paragraph view. </li>
                        </ul>
                    </div>
                </div>
            </h6>
            <div class="mh-caption-container">   
                <form>
                    <label for="mh-parse-caption">Search:</label>
                    <div class="mh-paragraph-icons">
                        <div class="mh-bullets"></div>
                        <div class="mh-paragraph"></div>
                    </div>
                    <br>
                    <input id="mh-parse-caption" type="text" class="mh-parse-caption"><div class="mh-caption-close"></div>
                    <div class="mh-transcript">
                        <ul></ul>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript">

    WDN.jQuery(document).ready(function(){

        track = <?php echo $jsonTrack; ?>;

        WDN.jQuery(".mh-caption-close").on("click", function(){

            WDN.jQuery(".mh-parse-caption").val("");

            WDN.jQuery(".mh-transcript ul li").addClass("highlight");

        });

        WDN.jQuery(".mh-parse-caption").on("keyup focus", function(){

            var search = WDN.jQuery(this).val().toLowerCase();

            for (var i = 0; i < track.subtitles.length; i++) {

                var line = track.subtitles[i].text.toLowerCase();

                if(line.search(search) > -1){

                    WDN.jQuery(".mh-transcript ul li").eq(i).addClass("highlight");

                }else{

                    WDN.jQuery(".mh-transcript ul li").eq(i).removeClass("highlight");

                }
            };

        });

        WDN.jQuery(".mh-paragraph-icons").on("click", function(){

            WDN.jQuery(".mh-caption-search").toggleClass("bulleted");

        });


        for (var i = 0; i < track.subtitles.length; i++) {

            var subtitles = track.subtitles[i].text.replace(/<(?:.|\n)*?>/gm, '');

            WDN.jQuery(".mh-transcript ul").append("<li class='highlight' onclick='jumpto("+track.subtitles[i].start*.001+")'><span>["+displaytime(track.subtitles[i].start)+"]</span>"+subtitles+"</li>");

        };

    });

    function jumpto(time){
        mejs.players.mep_0.setCurrentTime(time)
        WDN.jQuery("html, body").animate({ scrollTop: 0 }, "fast");
    }

    function escapeHtml(text) {

      var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };

      return text.replace(/[&<>"']/g, function(m) { return map[m]; });

    }

    function displaytime(millis){

        var hours = Math.floor(millis / 36e5),
            mins = Math.floor((millis % 36e5) / 6e4),
            secs = Math.floor((millis % 6e4) / 1000);

            if(secs < 10){
                secs = "0"+secs;
            }

            if(hours > 0){
                return hours+':'+mins+':'+secs;
            }else{
                return mins+':'+secs;
            };

    }

</script>

<?php endif; ?>

<script type="text/javascript">
    (function () {
        var e = function () {
            <?php if (isset($context->media->id) && $context->media->id) { ?>
            WDN.setPluginParam('mediaelement_wdn', 'options', {
                success: function (m, v, t) {
                    //Playcount

                    <?php if($jsonTrack): ?>

                        WDN.jQuery(".mejs-captions-button").before('<div class="mejs-button wide"><button type="button" class="wdn-icon-search caption-toggle" aria-controls="mep_0" title="Toggle Searchable Transcript" aria-label="Searchable Transcript"></button></div>')

                        WDN.jQuery(".caption-toggle").on("click", function(){

                            WDN.jQuery(".mh-caption-search").toggleClass("show");

                        });

                    <?php endif; ?>

                    var w = false, u = '<?php echo $controller->getURL($context->media) ?>';
                    m.addEventListener('play', function () {
                        if (!w) {
                            WDN.jQuery.post(u, {action: "playcount"});
                            w = true;
                        }
                    });

                    //Social Sharing via https://xparkmedia.com/blog/mediaelements-add-a-share-button-to-video-elements-using-jquery/
                    var initSharing = function(m, v) {
                        var $           = WDN.jQuery;
                        var $inner      = false;
                        var $video      = $(v);
                        var $title      = $video.attr('title');
                        var share_url   = $video.attr('data-url');
                        var media_type  = v.tagName.charAt(0).toUpperCase() + v.tagName.slice(1).toLowerCase();
                        var mediahub_id = $video.attr('data-mediahub-id');
                        
                        if (!share_url) {
                            return;
                        }
                        
                        if ($inner = $(v).parents('.mejs-container')) {
                            // share urls
                            var sharelinks = {
                                "wdn-icon-mail":     {title: 'Email', url:'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ' : ' + $title},
                                "wdn-icon-facebook":     {title: 'Facebook', url:'https://www.facebook.com/sharer/sharer.php?u=' + share_url},  // facebook
                                "wdn-icon-twitter":     {title: 'Twitter', url:'http://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url}, // twitter
                                "wdn-icon-linkedin-squared":     {title: 'LinkedIn', url:'http://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title='+ $title +'&summary=Checkout this '+ media_type +'%20&source=University%20of%20Nebraska%20-%20Lincoln%20MediaHub'} //google plus
                            }

                            //create share links
                            var links = '<li><a href="http://go.unl.edu/?url=referer" class="wdn-icon-link" rel="nofollow">Get a Go URL</a></li>';
                            for (var key in sharelinks) {
                                links += '<li class="outpost"><a href="'+sharelinks[key].url+'" rel="nofollow" target="_blank" class="'+key+'" title="Share on '+sharelinks[key].title+'">Share on '+sharelinks[key].title+'</a></li>';
                            }

                            var html = '<div class="media-content-head">';
                            html += '<div class="media-content-title"><a href="' + share_url + '">' + $title + '</a></div>';
                            html += '<div class="wdn-share-this-page">';
                            html += '<input type="checkbox" id="mh-share-toggle" value="Show share options" class="wdn-input-driver mh-share-toggle">'
                            html += '<label for="mh-share-toggle" class="wdn-icon-share">Share This Page</label>';
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
                    }

                    //Load the CSS
                    WDN.loadCSS('<?php echo UNL_MediaHub_Controller::$url; ?>templates/html/css/player.css', function() {
                        initSharing(m, v);
                    });

                }
            });
            <?php } ?>
            WDN.initializePlugin('mediaelement_wdn');
        };
        e();
    })();
</script>
