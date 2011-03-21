<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
chdir(dirname(__FILE__).'/../../');
require_once 'UNL/Auth.php';

//$auth = UNL_Auth::PEARFactory('CAS', $options=null, $loginfunction=null, false);
$auth = UNL_Auth::PEARFactory('CAS');
$auth->start();

if (isset($_GET['logout']) && $auth->checkAuth()) {
    $auth->logout();
    $auth->start();
}

if ($auth->checkAuth()) {
    /*
     * The output of your site goes here.
     */
    echo 'You are authenticated, '.$auth->getUsername().'<br />';
    echo '<a href="?logout">Logout</a>';
} else {
    echo 'You need to log in bro!';
}

?>