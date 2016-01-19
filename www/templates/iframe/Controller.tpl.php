<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Test</title>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/jquery.js"></script>
    <script src="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/mediaelement/mediaelement-and-player.js"></script>
    <link rel="stylesheet" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>templates/iframe/js/vendor/mediaelement/css/mediaelementplayer.min.css" />
    <link rel="stylesheet" href="<?php echo UNL_MediaHub_Controller::toAgnosticURL(UNL_MediaHub_Controller::$url); ?>templates/iframe/css/iframe.css?v=<?php echo UNL_MediaHub_Controller::VERSION ?>" />
    
    <style>
        body {
            margin: 0px;
        }
    </style>
</head>
<body>
    <?php echo $savvy->render($context->output); ?>
</body>
</html>
