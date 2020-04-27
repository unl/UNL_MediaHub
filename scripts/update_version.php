<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}


$command = 'cd ' . UNL_MediaHub::getRootDir() . ' && git describe --always';

$result = exec($command, $output, $status);

if (0 !== $status) {
    throw new Exception('Unable to get the git version');
}

$version = false;
if (0 === $status) {
    //it worked
    $version = trim($output[0]);
}

$current_version = UNL_MediaHub_Controller::getVersion();

if ($current_version != $version) {
    UNL_MediaHub_Controller::setVersion($version);
}
