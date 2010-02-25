<?php
function autoload($class)
{
    $class = str_replace('_', '/', $class);
    include $class . '.php';
}

spl_autoload_register("autoload");

// DSN for the mediyak database
$dsn = 'mysql://mediayak:mediayak@localhost/mediayak;unix_socket=/var/mysql/mysql.sock';

set_include_path(dirname(__FILE__).'/src'.PATH_SEPARATOR.dirname(__FILE__).'/lib/php'.PATH_SEPARATOR.get_include_path());

UNL_MediaYak_Controller::$url = 'http://localhost/workspace/UNL_MediaYak/www/';
UNL_MediaYak_Controller::$thumbnail_generator = 'http://itunes.unl.edu/thumbnails.php?url=';

