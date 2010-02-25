<?php
require_once 'UNL/Autoload.php';
require_once 'config.inc.php';
ini_set('magic_quotes_gpc', false);
ini_set('magic_quotes_runtime', false);

$controller = new UNL_MediaYak_Controller($dsn);

UNL_MediaYak_OutputController::display($controller);

