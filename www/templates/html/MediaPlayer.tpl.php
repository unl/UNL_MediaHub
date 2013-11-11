<?php
if ($context->isVideo()) {
    echo $savvy->render($context, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/Audio.tpl.php');
}
?>

<script type="text/javascript">
    (function () {
        var i, e = function () {
            <?php if (isset($context->id) && $context->id) { ?>
            i = <?php echo $context->id ?>;
            WDN.setPluginParam('mediaelement_wdn', 'options', {
                success: function (m, v) {
                    //Playcount
                    var w = false, u = '<?php echo $controller->getURL($context) ?>';
                    m.addEventListener('play', function () {
                        if (!w) {
                            WDN.jQuery.post(u, {action: "playcount"});
                            w = true;
                        }
                    });

                    //Social Sharing
                    var initSharing = function(m, v) {
                        if (v.tagName != 'VIDEO') {
                            return;
                        }
                        
                        //Load the CSS
                        WDN.loadCSS('<?php echo $controller->getURL(); ?>templates/html/css/share.css');

                        var $ = WDN.jQuery.noConflict( );
                        var $inner      = false;
                        var $video      = $(v);
                        var $title      = $video.attr('title');
                        var share_url   = $video.attr('data-url');
                        
                        if (!share_url) {
                            return;
                        }
                        
                        if ($inner = $(v).parents('.mejs-video')) {
                            // share urls
                            var sharelinks = {
                                tw:     'http://twitter.com/share?text=Video: ' + $title + '&url=' + share_url, // twitter
                                fb:     'https://www.facebook.com/sharer/sharer.php?url=' + share_url,	// facebook
                                gp:     'https://plus.google.com/share?url=' + share_url, //google plus
                                em:     'mailto:?body=Check out this video: ' + share_url + '&subject=Video : ' + $title
                            }

                            //create share links
                            var links = '';
                            for (var key in sharelinks) {
                                links += '<a href="#" rel="nofollow" class="'+key+'">';
                            }

                            $inner.prepend('<div class="media-content-title">' + $title + '</div>');
                            $inner.prepend('<a her="#" rel="nofollow" class="share-video-link">' + 'Share' + '</a>');

                            var html   = '<div class="share-video-form">';
                            html += '<em class="share-video-close">x</em><h4>' + 'share video' + '</h4>';
                            html += '<em>'+ 'link' +'</em><input type="text" class="share-video-lnk share-data" value="' + share_url + '" />' ;

                            html += '<div class="video-social-share">' + links + '</div>' ;
                            $inner.prepend(html + '</div>');

                            // start listeners
                            $sharelink  = $inner.find('.share-video-link');
                            $sharefrom  = $inner.find('.share-video-form');
                            $closelink  = $inner.find('.share-video-close');
                            $videotitle = $inner.find('.media-content-title');

                            // hide form when video is playing
                            m.addEventListener('play', function(e) {
                                $sharelink.hide(); $sharefrom.hide(); $videotitle.hide();
                            }, false );

                            // show form when video is paused
                            m.addEventListener('pause', function(e) {
                                $sharelink.removeClass('video-active');
                                $inner.find('.mejs-overlay-button').show( );
                                $videotitle.show();
                                $sharelink.show();
                            }, false );

                            // close video form
                            var video_close_share_form = function(){
                                $sharefrom.hide();
                                $sharelink.removeClass('video-active');

                                $inner.find('.mejs-overlay-play')
                                    .removeClass('share-overlay');
                            };

                            // show / hide video form
                            $closelink.bind('click', video_close_share_form );
                            $sharelink.bind('click', function(){

                                if( $sharefrom.is(':hidden')) {
                                    $sharefrom.show( );
                                    $sharelink.addClass('video-active');

                                    $inner.find('.mejs-overlay-play')
                                        .addClass('share-overlay').show();
                                } else {
                                    video_close_share_form();
                                }
                            });

                            // add share links listener
                            $inner.find('.video-social-share a').click( function(){
                                key = $(this).attr('class');
                                if(sharelinks[key]) {
                                    window.open(sharelinks[key]);
                                }
                            });
                        }
                    }

                    initSharing(m, v);
                }
            });
            <?php } ?>
            WDN.initializePlugin('mediaelement_wdn');
        };
        e();
    })();
</script>
