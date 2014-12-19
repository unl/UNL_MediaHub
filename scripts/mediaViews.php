<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}

//Establish mediahub connection
$media_hub = new UNL_MediaHub();

$db = Doctrine_Manager::getInstance()->getCurrentConnection();

//prune logs
$date = date('Y-m-d H:i:s', strtotime('1 month ago'));

$sql = "DELETE FROM media_views WHERE datecreated < ?";
$q   = $db->prepare($sql);
$q->execute(array($date));

//Update popular counts
$sql = "UPDATE media
INNER JOIN (
    SELECT media_id, COUNT(*) AS total_views
    FROM media_views
    GROUP BY media_id
  ) AS counts ON media.id = counts.media_id
SET popular_play_count = counts.total_views";
$q   = $db->prepare($sql);
$q->execute();