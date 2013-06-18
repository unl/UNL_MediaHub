<?php
require_once dirname(__FILE__).'/../config.inc.php';

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 3) {
        echo "This program requires 2 arguments.\n";
        echo "addUser.php channel-id username\n\n";
        echo "Example: addUser.php 126 hhusker2\n";
} else {
    require_once dirname(__FILE__).'/../config.inc.php';

    $my = new UNL_MediaHub($dsn);

    $feed = UNL_MediaHub_Feed::getById($_SERVER['argv'][1]);
    $user = UNL_MediaHub_User::getByUid($_SERVER['argv'][2]);
    $feed->addUser($user);

    echo "{$_SERVER['argv'][2]} has been added to {$feed->title}.\n";
}
exit();
