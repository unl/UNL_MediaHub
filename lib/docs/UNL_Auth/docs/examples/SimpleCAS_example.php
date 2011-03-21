<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
chdir(dirname(__FILE__).'/../../');
require_once 'UNL/Auth.php';

$auth = UNL_Auth::factory('SimpleCAS');

if (isset($_GET['login'])) {
    $auth->login();
} elseif (isset($_GET['logout'])) {
    $auth->logout();
}

if (!$auth->isLoggedIn()) {
    // Could call $auth->login() here to get the party started.
    echo "You are not logged in.\n<br />\n";
    echo '<a href="?login=true">Click here to log in!</a>';
} else {
    echo "You are logged in as {$auth->getUser()}<br />";
    echo "<a href='?logout'>logout</a>";
}