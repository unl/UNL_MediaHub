<?php
ini_set('pcre.jit', '0');
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

// Auth Service
UNL_MediaHub_AuthService::$provider = new UNL_MediaHub_AuthService_UNL();

// Controller Settings
UNL_MediaHub_Controller::$url = 'http://localhost:8007/';
UNL_MediaHub_Controller::$thumbnail_generator = 'https://itunes.unl.edu/thumbnails.php?url=';

UNL_MediaHub_AmaraAPI::$amara_username = false;
UNL_MediaHub_AmaraAPI::$amara_api_key  = false;

UNL_MediaHub_RevAPI::$host = \RevAPI\Rev::SANDBOX_HOST;
UNL_MediaHub_RevAPI::$client_api_key = '';
UNL_MediaHub_RevAPI::$user_api_key = '';
UNL_MediaHub_RevAPI::$http_config = array();

// Set custom mediahub namespaced item elements
$itemElements = array(
    'water_af'  => 'Volume of water in acre-feet (af)',
    'water_cfs' => 'Speed of water in cubic feet per second (cfs)',
    'creation_date' => 'Date of the creation of the media',
    );

UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::setCustomElements($itemElements);

$cache = new UNL_MediaHub_CacheInterface_Mock();

/*
// Set a few caching options
$options = array(
    'cacheDir' => __dir__ . '/tmp/cache/',
    'lifeTime' => 60*60*24*1 //cache for 1 days
);
// Create a Cache_Lite object
$cache = new \Savvy_Turbo_CacheInterface_UNLCacheLite($options);
*/
