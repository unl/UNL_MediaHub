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
$sql = "DELETE FROM media_views WHERE datecreated < (NOW() - INTERVAL 1 MONTH)";
$q   = $db->prepare($sql);
$q->execute();

//Update popular counts (more of a weight than an actual count)
$sql = "
UPDATE media
INNER JOIN (
    SELECT media_id, COUNT(*) AS total_views
    FROM media_views
    WHERE media_views.datecreated > (NOW() - INTERVAL 1 WEEK)
    GROUP BY media_id
  ) AS seven_day_counts ON media.id = seven_day_counts.media_id
INNER JOIN (
    SELECT media_id, COUNT(*) AS total_views
    FROM media_views
    WHERE media_views.datecreated > (NOW() - INTERVAL 30 DAY)
    GROUP BY media_id
  ) AS thirty_day_counts ON media.id = thirty_day_counts.media_id
SET popular_play_count = round(seven_day_counts.total_views + thirty_day_counts.total_views*.5 + media.play_count*.15)";
$q   = $db->prepare($sql);
$q->execute();