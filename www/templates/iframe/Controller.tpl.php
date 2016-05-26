<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Test</title>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/jquery.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/mediaelement/mediaelement-and-player.js?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>"></script>
    <link rel="stylesheet" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/mediaelement/css/mediaelementplayer.min.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" />
    <link rel="stylesheet" href="<?php echo UNL_MediaHub_Controller::toAgnosticURL(UNL_MediaHub_Controller::$url); ?>templates/iframe/css/iframe.css?v=<?php echo UNL_MediaHub_Controller::getVersion() ?>" />
    
    <style>
        html, body {
            margin: 0px;
            height: 100%;
            background-color: #000;
        }
        .mejs-video {
            position: static !important;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body>
    <?php echo $savvy->render($context->output); ?>
</body>
</html>
