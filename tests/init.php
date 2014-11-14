<?php
//load the root config
$config_file = __DIR__ . '/../config.sample.php';
if (file_exists(__DIR__ . '/../config.inc.php')) {
    $config_file = __DIR__ . '/../config.inc.php';
}
require_once $config_file;

//Make sure that the default DSN is NEVER used
UNL_MediaHub::$dsn = '';

//load the tests config (to change the DSN, etc)
$config_file = __DIR__ . '/config.sample.php';
if (file_exists(__DIR__ . '/config.inc.php')) {
    $config_file = __DIR__ . '/config.inc.php';
}
require_once $config_file;
