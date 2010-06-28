<?php

// DSN for the mediyak database
$dsn = 'mysql://mediayak:mediayak@localhost/mediayak';

set_include_path(dirname(__FILE__).'/src'.PATH_SEPARATOR.dirname(__FILE__).'/lib/php');

require_once 'UNL/Autoload.php';

UNL_MediaYak_Controller::$url = 'http://localhost/workspace/UNL_MediaYak/www/';
UNL_MediaYak_Controller::$thumbnail_generator = 'http://itunes.unl.edu/thumbnails.php?url=';

