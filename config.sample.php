<?php

// DSN for the mediyak database
$dsn = 'mysql://mediayak:mediayak@localhost/mediayak;unix_socket=/var/mysql/mysql.sock';

set_include_path(dirname(__FILE__).PATH_SEPARATOR.get_include_path());

UNL_MediaYak_Controller::$url = 'http://ucommbieber.unl.edu/workspace/UNL_MediaYak/';
UNL_MediaYak_Controller::$thumbnail_generator = 'http://ucommbieber.unl.edu/workspace/UNL_iTunesU/thumbnails.php?url=';

