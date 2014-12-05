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
                        var mediahub_id = $video.attr('data-mediahub-id');
                        
                        if (!share_url) {
                            return;
                        }
                        
                        if ($inner = $(v).parents('.mejs-container')) {
                            // share urls
                            var sharelinks = {
                                "wdn-icon-twitter":     {title: 'Twitter', url:'http://twitter.com/share?text=' + media_type + ': ' + $title + '&url=' + share_url}, // twitter
                                "wdn-icon-facebook":     {title: 'Facebook', url:'https://www.facebook.com/sharer/sharer.php?u=' + share_url},	// facebook
                                "wdn-icon-gplus":     {title: 'Google Plus', url:'https://plus.google.com/share?url=' + share_url}, //google plus
                                "wdn-icon-mail":     {title: 'Email', url:'mailto:?body=Checkout this ' + media_type + ': ' + share_url + '&subject=' + media_type + ' : ' + $title}
                            }

                            //create share links
                            var links = '';
                            for (var key in sharelinks) {
                                //links += '<a href="" rel="nofollow" class="'+key+'" target="_blank" title="Share on '+sharelinks[key].title+'"></a>';
                                links += '<a href="'+sharelinks[key].url+'" rel="nofollow" target="_blank" class="'+key+'" title="Share on '+sharelinks[key].title+'"></a>';
                            }

                            var html = '<div class="media-content-head">';
                            html += '<div class="media-content-title">' + $title + '</div>';
                            html += '<a href="#" rel="nofollow" class="share-video-link"></a>';
                            html += '<div class="share-video-form">';
                            html += '<em class="share-video-close">x</em>';
                            html += '<h4>' + 'share this video' + '</h4>';
                            html += '<label for="share-video-lnk-'+mediahub_id+'"><em>'+ 'link' +'</em></label>';
                            html += '<input type="text" id="share-video-lnk-'+mediahub_id+'" class="share-video-lnk share-data" value="' + share_url + '" />' ;

                            html += '<div class="wdn-pull-right mh-share-video">' + links + '</div>' ;
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
