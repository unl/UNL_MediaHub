<?php
require_once dirname(__FILE__).'/../config.inc.php';

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
  || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 3) {
  echo "This program requires 2 arguments.\n";
  echo "bulkAddUser.php channel-id user-list-file\n\n";
  echo "Example: bulkAddUser.php 126 new-users.txt\n";
} else {
  require_once dirname(__FILE__).'/../config.inc.php';

  $my = new UNL_MediaHub();
  $feed = UNL_MediaHub_Feed::getById($_SERVER['argv'][1]);
  $userFilename = $_SERVER['argv'][2];
  $handle = fopen($userFilename, "r");
  if ($handle) {
    while (($data = fgets($handle)) !== false) {
      // process the line read.
      $username = trim(strtolower($data));
      if (!empty($username)) {
        $user = UNL_MediaHub_User::getByUid($username);
        $feed->removeUser($user);
        $feed->addUser($user);
        echo "{$username} has been added to {$feed->title}.\n";
      }
    }
    fclose($handle);
  } else {
    echo "{$userFilename} could not be opened.\n";
  }


}
exit();