<?php
if (file_exists(dirname(__FILE__).'/config.inc.php')) {
    require_once dirname(__FILE__).'/config.inc.php';
} else {
    require dirname(__FILE__).'/config.sample.php';
}

echo 'Connecting to the database&hellip;';
$mediahub = new UNL_MediaHub($dsn);
$db = $mediahub->getDB();
echo 'connected successfully!<br />';
/**
 * 
 * Enter description here ...
 * @param Doctrine_Connection $db
 * @param $sql
 * @param $message
 * @param $fail_ok
 */
function exec_sql($db, $sql, $message, $fail_ok = false)
{
	echo $message.'&hellip;';
	try {
	   $result = $db->execute($sql);
	} catch (Exception $e) {
		if (!$fail_ok) {
			echo 'The query failed:'.$db->errorInfo();
			exit();
		}
	}
	echo 'finished.<br />';
}
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/mediahub.sql'), 'Initializing database structure');
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/add_dateupdated.sql'), 'Add date updated', true);
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/add_feed_image.sql'), 'Adding feed image support', true);
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/add_featured_feed_fields.sql'), 'Adding featured feeds support', true);
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/add_media_privacy.sql'), 'Adding media privacy settings', true);
exec_sql($db, file_get_contents(dirname(__FILE__).'/data/add_media_play_count.sql'), 'Adding media play count', true);

echo 'Upgrade complete!';
