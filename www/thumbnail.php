<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require dirname(__FILE__).'/../config.sample.php';
}

// Send headers for CORS support so poster images can be used when crossorigin="anonymous"
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With');
if (isset($_SERVER['REQUEST_METHOD'])
    && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // short circuit execution for CORS OPTIONS reqeusts
    exit();
}

//Validate input
if (!isset($_GET['media_id'])) {
    throw new \Exception('You must provide a media_id', 400);
}

$media_id = (int)$_GET['media_id'];

/**
 * Create the cache key
 * Note that the cache key does not contain the time code (only the url of the media).
 * To create thumbnail at at specific time of the video, you must make a request to something like
 * thumbnail.php?rebuild&time=00:00:10.00&url=x
 * Subsequent requests to thumbnail.php?url=x will yield the last generated thumbnail
 */
$directory = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/'.$media_id;
$file = $directory.'/original.jpg';

if (isset($_GET['rebuild']) || !file_exists($file)) {
    //First, establish DB connection
    $media_hub = new UNL_MediaHub();
    
    if (!$media = UNL_MediaHub_Media::getById($media_id)) {
        throw new \Exception('media not found', 404);
    }
    
    //Does the current user have permission?
    $user = UNL_MediaHub_AuthService::getInstance()->getUser();
    $user_can_edit = false;
    
    if ($user) {
        $user_can_edit = $media->userCanEdit(UNL_MediaHub_AuthService::getInstance()->getUser());
    }
    
    if (!$user_can_edit && file_exists($file)) {
        //A user can't create a thumbnail for a video if one already exists (unless they have permission to edit the video)
        throw new \Exception('User does not have permission to edit thumbnail', 400);
    }

    //figure out the time
    $time = '00:00:10.00';
    if ($user_can_edit 
        && isset($_GET['time'])
        && preg_match('/^[\d]+\:[\d]{2}\:[\d]{2}(\.[\d]{2})?$/', $_GET['time'])) {
        //Allow customizing the time if the user has permission
        $time = escapeshellarg($_GET['time']);
    }
    
    //We need to cache data
    $url = $media->url;
    $return = array();
    $status = 1;
    
    if ($media->getLocalFileName()) {
        $url = $media->getLocalFileName();
    }
    
    $url = escapeshellarg($url);

    exec(UNL_MediaHub::getRootDir() . "/ffmpeg/ffmpeg -i $url -ss $time -vcodec mjpeg -vframes 1 -f image2 /tmp/posterframe.jpg -y", $return, $status);

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    if ($status == 0) {
        $data = file_get_contents('/tmp/posterframe.jpg');

        file_put_contents($file, $data);
    } else {
        $data = file_get_contents(UNL_MediaHub::getRootDir() . '/data/video-placeholder.jpg');
    }
} else {
    //just a quick retrieval
    $data = file_get_contents($file);
}

header('Content-type: image/jpeg');
echo($data);
