<?php

require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

$controller = new UNL_MediaYak_Controller($dsn);

$feed_url = $all_items_feed = 'http://pipes.yahoo.com/pipes/pipe.run?_id=facba651e446f1754f729a8edd6e1083&_render=php';
$recent_items_feed = 'http://pipes.yahoo.com/pipes/pipe.run?_id=fc9a8e08fbaa48a6da49e871fbea3d24&_render=php';

$mediayak = new UNL_MediaYak($dsn);

$mediayak->addFromYahooPipe($feed_url);


?>