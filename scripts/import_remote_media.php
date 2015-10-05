<?php

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
    echo "This program requires 1 argument1.\n";
    echo "addMedia.php media_id\n\n";
    echo "Example: addMedia.php 25\n";
}

require_once dirname(__FILE__).'/../config.inc.php';

$mediahub = new UNL_MediaHub();

$media_id = $_SERVER['argv'][1];

$media = UNL_MediaHub_Media::getById($media_id);

if (!$media) {
    echo 'Unable to find media' . PHP_EOL;
    exit();
}

if ($media->getLocalFileName()) {
    echo 'This media is already local, we are unable to import it' . PHP_EOL;
    exit();
}


$file = file_get_contents($media->url);

if (!$file) {
    echo 'Unable to get the remote media' . PHP_EOL;
    exit();
}


$extension = strtolower(pathinfo($media->url, PATHINFO_EXTENSION));
$file_name = md5(microtime() + rand()) . '.'. $extension;
$file_path = UNL_MediaHub_Manager::getUploadDirectory() . DIRECTORY_SEPARATOR . $file_name;

if (file_exists($file_path)) {
    echo 'file already exists' . PHP_EOL;
    exit();
}

file_put_contents($file_path, $file);

$url = UNL_MediaHub_Controller::$url.'uploads/'.$file_name;

echo 'previous URL: ' . $media->url . PHP_EOL;
echo 'new URL: ' . $url . PHP_EOL;

$media->url = $url;
$media->dateupdated = $media->dateupdated = date('Y-m-d H:i:s');
$media->save();

echo 'finished!' . PHP_EOL;
