<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

// DSN for the mediyak database
$dsn = 'mysql://mediahub:mediahub@localhost/mediahub';

set_include_path(dirname(__FILE__).'/src'.PATH_SEPARATOR.dirname(__FILE__).'/lib/php'.PATH_SEPARATOR.dirname(__FILE__).'/vendor/simple-cas/simple-cas/src');

require_once 'UNL/MediaHub.php';
UNL_MediaHub::registerAutoloaders();

UNL_MediaHub_Controller::$url = 'http://localhost:8007/';
UNL_MediaHub_Controller::$thumbnail_generator = 'http://itunes.unl.edu/thumbnails.php?url=';


// Set custom mediahub namespaced item elements
$itemElements = array(
    'water_af'  => 'Volume of water in acre-feet (af)',
    'water_cfs' => 'Speed of water in cubic feet per second (cfs)',
    'creation_date' => 'Date of the creation of the media',
    );

UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::setCustomElements($itemElements);

$cache = new UNL_MediaHub_CacheInterface_Mock();
