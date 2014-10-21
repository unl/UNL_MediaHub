<?php
/**
 * This script is used to initialize a test script (set up config, autoloaders, etc).
 */
$config_file = __DIR__ . '/../config.sample.php';
if (file_exists(__DIR__ . '/../config.inc.php')) {
    $config_file = __DIR__ . '/../config.inc.php';
}

require_once $config_file;
