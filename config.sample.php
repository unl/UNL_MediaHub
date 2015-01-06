<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

set_include_path(
    get_include_path()
    .PATH_SEPARATOR.dirname(__FILE__).'/src'
    .PATH_SEPARATOR.dirname(__FILE__).'/lib/php'
    .PATH_SEPARATOR.dirname(__FILE__).'/vendor/simple-cas/simple-cas/src'
    .PATH_SEPARATOR.dirname(__FILE__).'/vendor/UNL/unl_cache_lite'
);

require_once 'UNL/MediaHub.php';
UNL_MediaHub::registerAutoloaders();

// DSN for the mediyak database
UNL_MediaHub::$dsn = 'mysql://mediahub:mediahub@localhost/mediahub';

UNL_MediaHub_Controller::$url = 'http://localhost:8007/';
UNL_MediaHub_Controller::$thumbnail_generator = 'http://itunes.unl.edu/thumbnails.php?url=';

UNL_MediaHub_AmaraAPI::$amara_username = false;
UNL_MediaHub_AmaraAPI::$amara_api_key  = false;

// Set custom mediahub namespaced item elements
$itemElements = array(
    'water_af'  => 'Volume of water in acre-feet (af)',
    'water_cfs' => 'Speed of water in cubic feet per second (cfs)',
    'creation_date' => 'Date of the creation of the media',
    );

UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::setCustomElements($itemElements);

$cache = new UNL_MediaHub_CacheInterface_Mock();
