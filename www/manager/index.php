<?php

require_once dirname(__FILE__).'/../../config.inc.php';

$manager = new UNL_MediaHub_Manager($_GET, $dsn);

if (!$manager->isLoggedIn()) {
    throw new Exception('Not logged in!');
}

$outputcontroller = new UNL_MediaHub_OutputController();
$outputcontroller->setCacheInterface(new UNL_MediaHub_CacheInterface_Mock());
$outputcontroller->setTemplatePath(dirname(dirname(__FILE__)).'/templates/html');
$outputcontroller->addTemplatePath(dirname(__FILE__).'/templates');

echo $outputcontroller->render($manager);

