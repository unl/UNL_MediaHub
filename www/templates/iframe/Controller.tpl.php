<?php
$is_audio = false;
$is_360 = false;
$has_hls = false;
$title = '';
if (isset($context->output[0]) && $context->output[0] instanceof UNL_MediaHub_Media) {
    $is_audio = !$context->output[0]->isVideo();
    $title = $context->output[0]->title;
    $is_360 = $context->output[0]->is360();
    $has_hls = $context->output[0]->hasHls();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>MediaHub Media</title>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/jquery.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/three.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <link href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/video-js.min.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" rel="stylesheet">
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/video.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <?php if ($is_audio): ?>
        <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/wavesurfer.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
        <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs.wavesurfer.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
        <link href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs.wavesurfer.min.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" rel="stylesheet">
        <link href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/css/font-awesome.min.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" rel="stylesheet">
    <?php endif; ?>
    <?php if ($is_360): ?>
        <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs-panorama.v5.min.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
        <link href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs-panorama.min.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" rel="stylesheet">
    <?php endif; ?>
    
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs-mediahub.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/videojs-captions-toggle.js"></script>
    <link rel="stylesheet" href="<?php echo UNL_MediaHub_Controller::toAgnosticURL(UNL_MediaHub_Controller::$url); ?>templates/iframe/css/iframe.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" />
    
    <style>
        html, body {
            margin: 0px;
            height: 100%;
            background-color: #000;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <?php echo $savvy->render($context->output); ?>
    <script>
        var video = document.getElementsByTagName("video")[0];
        if (video) {
          window.addEventListener('message', function (event) {
            switch(event.data) {
              case 'mh-play-video':
                video.play();
                break;
              case 'mh-reset-video':
                video.pause();
                video.currentTime = 0;
                break;
              case 'mh-pause-video':
                video.pause();
                break;
              default:
                // do nothing
            }
          });
        }
    </script>
</body>
</html>
