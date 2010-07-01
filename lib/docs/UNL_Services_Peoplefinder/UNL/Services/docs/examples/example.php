<?php
/**
 * Simple example to get the full name of a user from a UID.
 * 
 * @package UNL_Services_Peoplefinder
 */

/**
 * Include the Peoplefinder services file.
 */
require_once 'UNL/Services/Peoplefinder.php';
$uid = 'bbieber2';
echo 'The user '.$uid.' is '.UNL_Services_Peoplefinder::getFullName($uid);

?>