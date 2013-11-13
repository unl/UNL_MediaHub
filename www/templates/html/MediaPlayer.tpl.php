<?php
if ($context->isVideo()) {
    echo $savvy->render($context, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/Audio.tpl.php');
}
?>

<script type="text/javascript">
    (function () {
        var e = function () {
            <?php if (isset($context->id) && $context->id) { ?>
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

                    //Social Sharing via https://xparkmedia.com/blog/mediaelements-add-a-share-button-to-video-elements-using-jquery/
                    var initSharing = function(m, v) {
                        var $           = WDN.jQuery;
                        var $inner      = false;
                        var $video      = $(v);
                        var $title      = $video.attr('title');
                        var share_url   = $video.attr('data-url');
                        var media_type  = v.tagName.charAt(0).toUpperCase() + v.tagName.slice(1).toLowerCase();
                        
                        if (!share_url) {
                            return;
                        }
                        
                        if ($inner = $(v).parents('.mejs-container')) {
                            // share urls
                            var sharelinks = {
                                tw:     'http://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url, // twitter
                                fb:     'https://www.facebook.com/sharer/sharer.php?u=' + share_url,	// facebook
                                gp:     'https://plus.google.com/share?url=' + share_url, //google plus
                                em:     'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ' : ' + $title
                            }

                            //create share links
                            var links = '';
                            for (var key in sharelinks) {
                                links += '<a href="#" rel="nofollow" class="'+key+'"></a>';
                            }

                            var html = '<div class="media-content-head">';
                            html += '<div class="media-content-title">' + $title + '</div>';
                            html += '<a href="#" rel="nofollow" class="share-video-link">' + 'Share' + '</a>';
                            html += '<div class="share-video-form">';
                            html += '<em class="share-video-close">x</em><h4>' + 'share this video' + '</h4>';
                            html += '<em>'+ 'link' +'</em><input type="text" class="share-video-lnk share-data" value="' + share_url + '" />' ;

                            html += '<div class="video-social-share">' + links + '</div>' ;
                            html += '</div>';
                            html += '</div>';
                            $inner.prepend(html);

                            // start listeners
                            $sharelink  = $inner.find('.share-video-link');
                            $sharefrom  = $inner.find('.share-video-form');
                            $closelink  = $inner.find('.share-video-close');
                            $videotitle = $inner.find('.media-content-title');
                            $videohead  = $inner.find('.media-content-head');

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
                            //TODO: fix this.
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

                            // add share links listener
                            $inner.find('.video-social-share a').click(function(e){
                                e.preventDefault();
                                key = $(this).attr('class');
                                if(sharelinks[key]) {
                                    window.open(sharelinks[key]);
                                }
                            });
                        }
                    }

                    //Load the CSS
                    WDN.loadCSS('<?php echo UNL_MediaHub_Controller::$url; ?>templates/html/css/share.css', function() {
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
