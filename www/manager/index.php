<?php

require_once dirname(__FILE__).'/../../config.inc.php';

$manager = new UNL_MediaYak_Manager($_GET, $dsn);
UNL_MediaYak_OutputController::setCacheInterface(new UNL_MediaYak_CacheInterface_Mock());
if ($manager->isLoggedIn()) {
    $outputcontroller = new UNL_MediaYak_OutputController();
    $outputcontroller->setTemplatePath(dirname(__FILE__).'/templates');

    echo $outputcontroller->render($manager);

} else {
    throw new Exception('Not logged in!');
}
