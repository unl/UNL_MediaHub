<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';
ini_set('magic_quotes_gpc', false);
ini_set('magic_quotes_runtime', false);

$controller = new UNL_MediaYak_Controller($dsn);

$outputcontroller = new UNL_MediaYak_OutputController();
$outputcontroller->setTemplatePath(dirname(__FILE__).'/templates/html');


switch($controller->options['format']) {
    case 'rss':
    case 'xml':
    case 'json':
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$controller->options['format']);
        break;
    default:            
}

echo $outputcontroller->render($controller);