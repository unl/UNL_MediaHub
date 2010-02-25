<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';
ini_set('magic_quotes_gpc', false);
ini_set('magic_quotes_runtime', false);

$controller = new UNL_MediaYak_Controller($dsn);

$outputcontroller = new UNL_MediaYak_OutputController();
$outputcontroller->setTemplatePath(dirname(__FILE__).'/templates');

echo $outputcontroller->render($controller);