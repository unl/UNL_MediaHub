<?php
if (file_exists(dirname(__FILE__).'/config.inc.php')) {
    require_once dirname(__FILE__).'/config.inc.php';
} else {
    require dirname(__FILE__).'/config.sample.php';
}

echo 'Connecting to the database&hellip;';
$mediahub  = new UNL_MediaHub();
$installer = new UNL_MediaHub_Installer($mediahub);
$messages  = $installer->install();

foreach ($messages as $message) {
    echo $message . PHP_EOL;
}

echo 'Upgrade complete!';
