<?php
if ($context->media->isVideo()) {
    echo $savvy->render($context->media, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context->media, 'MediaPlayer/Audio.tpl.php');
}
?>

<script type="text/javascript">
    (function () {
        var e = function () {
            <?php if (isset($context->media->id) && $context->media->id) { ?>
            WDN.setPluginParam('mediaelement_wdn', 'options', {
                success: function (m, v) {
                    var $      = WDN.jQuery;
                    var $video = $(v);
                    
                    //Playcount
                    var w = false, u = '<?php echo $controller->getURL($context->media) ?>';
                    m.addEventListener('play', function () {
                        if (!w) {
                            WDN.jQuery.post(u, {action: "playcount"});
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
                        var mediahub_id = $video.attr('data-mediahub-id');
                        
                        if (!share_url) {
                            return;
                        }
                        
                        if ($inner = $video.parents('.mejs-container')) {
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
                    WDN.loadCSS('<?php echo UNL_MediaHub_Controller::$url; ?>templates/html/css/player.css?v=<?php echo UNL_MediaHub_Controller::VERSION ?>', function() {
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
