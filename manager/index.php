<?php

require_once 'UNL/Autoload.php';
require_once '../config.inc.php';


$manager = new UNL_MediaYak_Manager();
if ($manager->isLoggedIn()) {
    UNL_MediaYak_OutputController::display($manager);
} else {
    throw new Exception('Not logged in!');
}
?>