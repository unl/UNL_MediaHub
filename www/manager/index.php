<?php

require_once dirname(__FILE__).'/../../config.inc.php';

$manager = new UNL_MediaHub_Manager($_GET);

if (!UNL_MediaHub_AuthService::getInstance()->getUser()) {
    throw new Exception('Not logged in!');
}

$outputcontroller = new UNL_MediaHub_OutputController();
$outputcontroller->addGlobal('controller', $manager);
$outputcontroller->setCacheInterface(new UNL_MediaHub_CacheInterface_Mock());
$outputcontroller->setTemplatePath(dirname(dirname(__FILE__)).'/templates/html');

switch($manager->options['format']) {
    case 'rss':
    case 'xml':
    case 'js':
    case 'html':
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$manager->options['format']);
        break;
    case 'json':
        header('Content-type:application/json');
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$manager->options['format']);
        break;
    default:
}

echo $outputcontroller->render($manager);

