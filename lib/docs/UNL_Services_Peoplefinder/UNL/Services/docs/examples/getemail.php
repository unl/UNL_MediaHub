<?php
/**
 * Simple example to get the email address from a UNL uid.
 * 
 * @package UNL_Services_Peoplefinder
 */

/**
 * Include the Peoplefinder services file.
 */
require_once 'UNL/Services/Peoplefinder.php';
$uid = 'bbieber2';
echo 'The email address for '.$uid.' is '.UNL_Services_Peoplefinder::getEmail($uid).'<br />';
highlight_file(__FILE__);
?>