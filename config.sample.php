<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

// DSN for the mediyak database
$dsn = 'mysql://mediahub:mediahub@localhost/mediahub';

set_include_path(dirname(__FILE__).'/src'.PATH_SEPARATOR.dirname(__FILE__).'/lib/php');

require_once 'UNL/MediaHub.php';
UNL_MediaHub::registerAutoloaders();

UNL_MediaHub_Controller::$url = 'http://localhost/workspace/UNL_MediaHub/www/';
UNL_MediaHub_Controller::$thumbnail_generator = 'http://itunes.unl.edu/thumbnails.php?url=';

$cache = new UNL_MediaHub_CacheInterface_Mock();
