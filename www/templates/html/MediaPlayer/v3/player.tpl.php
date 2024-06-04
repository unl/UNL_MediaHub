<div class="mediahub-embed non-framework">
    <?php
    if ($context->media->isVideo()) {
        echo $savvy->render($context->media, 'MediaPlayer/v3/Video.tpl.php');
    } else {
        echo $savvy->render($context->media, 'MediaPlayer/v3/Audio.tpl.php');
    }

    $getTracks = $context->media->getTextTrackURLs();
    $is360 = $context->media->is360();
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
        <?php if (isset($context->media->id) && $context->media->id): ?>
            var options = {
                videoWidth: '100%',
                videoHeight: '100%',
                controls: true,
                fluid: false
            };
            
            <?php if($context->media->hasHLS()): ?>
                options.html5 = {
                    hls: {
                        //default to a reasonable bandwidth so that we load 540p by default and then go up or down if we need to
                        bandwidth: 3200000
                    }
                };
            <?php endif; ?>
        <?php endif; ?>

        $(function() {
            $('video, audio').each(function() {
                var $media = $(this);
                var videoElement = $media.get(0);
                if (isMobile() && videoElement.tagName === 'AUDIO') {
                    videoElement.setAttribute('controls', '');
                    videoElement.style.width = '90%';
                    videoElement.style.height = '100px';
                    videoElement.style.margin = '0 auto';
                    return;
                }

                var autoplay = false;
                if (window.location.search.indexOf("autoplay=1") > -1) { // add ability to hide video titles. 
                    autoplay = true;
                }
                var captions = false;
                if (window.location.search.indexOf("captions=en") > -1) {
                  captions = true;
                }
                var id = $media.attr("id");
                var startLanguage = $media.attr('data-start-language');
                var is360 = <?php echo json_encode($is360) ?>;

                if ("undefined" !== typeof startLanguage) {
                    options.startLanguage = startLanguage;
                }

                if (videoElement.tagName === 'AUDIO') {
                    options.plugins = {};
                    options.plugins.wavesurfer = {
                        src: $media.attr('src'),
                        msDisplayMax: 10,
                        waveColor: '#D00000',
                        progressColor: '#FEFDFA',
                        cursorColor: '#FEFDFA',
                        backend: 'MediaElement',
                        mediaType: 'audio',
                        responsive: true
                    };
                    options.fluid = false;
                    options.fill = true;
                }

                (function(window, videojs) {

                    var player = window.player = videojs($media.get(0), options, function () {
                        window.addEventListener("resize", function () {
                            var canvas = player.getChild('Canvas');
                            if(canvas){
                                canvas.handleResize();
                                canvas.el().style.transform = "matrix(1, 0, 0, 1, "+window.innerWidth*-.5+", 0)"; // chrome resize bug shim
                            }
                        });

                        // This is needed for videojs-wavesurfer for some reason
                        if (videoElement.tagName === 'AUDIO') {
                            player.src($media.attr('src'));
                        }
                    });

                    //Set starttime if valid
                    player.on('loadedmetadata', function() {
                        try {
                            const queryString = $media.get(0).baseURI;
                            const urlParams = new URLSearchParams(queryString);
                            const startTime = parseFloat(urlParams.get('t'));
                            if (startTime && !isNaN(startTime)) {
                                if (videoElement.tagName === 'AUDIO') {
                                    const audioPlayer = player.wavesurfer().surfer;
                                    audioPlayer.on('ready', function(event) {
                                        if (startTime < audioPlayer.getDuration()) {
                                            audioPlayer.skipForward(startTime);
                                        }
                                    });
                                } else if (startTime < player.duration()) {
                                    player.currentTime(startTime);
                                }
                            }
                       } catch(e) {
                            // do nothing
                       }
                    });

                    if (videoElement.tagName === 'AUDIO') {
                        //Show loading indicator while loading the audio file
                        player.addClass('vjs-waiting');
                        player.on('waveReady', function() {
                            player.removeClass('vjs-waiting');
                        });
                    }

                    player.ready(function () {
                        if(!isMobile() && autoplay === true){
                            player.play();
                        }
                    });
                    
                    player.toggleSingleCaptionTrack({activeColor: "#D00000"});
                    player.MediahubPlayer({
                        privacy: "<?php echo UNL_MediaHub::escape($context->media->privacy); ?>",
                        url:'<?php echo UNL_MediaHub_Controller::toAgnosticURL($controller->getURL($context->media)); ?>',
                        captions: captions
                    });

                    <?php
                    $user = UNL_MediaHub_AuthService::getInstance()->getUser();
                    $user_can_edit = false;

                    if ($user) {
                        $user_can_edit = $context->media->userCanEdit($user);
                    }

                    if($user_can_edit): ?>
                    player.on("timeupdate", function(){
                        window.parent.postMessage({currentTime: player.currentTime()}, "*");
                    });
                    <?php endif; ?>

                    var width = videoElement.offsetWidth;
                    var height = videoElement.offsetHeight;
                    player.width(width), player.height(height);
                    if(is360){
                        player.panorama({
                            clickToToggle: (!isMobile()),
                            clickAndDrag: true,
                            autoMobileOrientation: true,
                            initFov: 100,
                            Notice: { Message: (isMobile())? "please move your phone" : "please use your mouse drag and drop the video"}
                        });
                    }
                }(window, window.videojs));
            });
        });
    })();

    function isMobile() {
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }
</script>
