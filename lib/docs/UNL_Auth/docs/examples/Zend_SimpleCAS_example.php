<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
chdir(dirname(__FILE__).'/../../');
require_once 'UNL/Auth.php';
require_once 'Zend/Auth.php';

$auth = Zend_Auth::getInstance();
$authAdapter = UNL_Auth::ZendFactory('SimpleCAS');
if (!$auth->hasIdentity()) {
    $result = $auth->authenticate($authAdapter);
    if (!$result->isValid()) {
        // Authentication failed; print the reasons why
        foreach ($result->getMessages() as $message) {
            echo "$message\n";
        }
    } else {
        // Authentication succeeded; the identity ($username) is stored
        // in the session
        // $result->getIdentity() === $auth->getIdentity()
        echo 'Hello '.$result->getIdentity();
    }
} else {
    echo 'Hello@';
}

