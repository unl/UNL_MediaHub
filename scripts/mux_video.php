<?php
require_once dirname(__FILE__).'/../config.inc.php';

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
    echo "This program requires 1 arguments.".PHP_EOL;
    echo "mux_video.php video-id".PHP_EOL;
    exit();
}

require_once dirname(__FILE__).'/../config.inc.php';

$my = new UNL_MediaHub();

$media = UNL_MediaHub_Media::getById($_SERVER['argv'][1]);

if (!$media) {
    echo 'Video Not Found' . PHP_EOL;
    exit();
}
$muxer = new UNL_MediaHub_Muxer($media);

$result = $muxer->mux();

echo 'DONE' . PHP_EOL;
