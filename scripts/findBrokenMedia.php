<?php
require_once dirname(__FILE__).'/../config.inc.php';

//ini_set('display_errors', true);

$mediahub = new UNL_MediaHub();

$list = new UNL_MediaHub_MediaList(array('limit'=>99999));
$list->run();

if (!count($list->items)) {
	echo 'no records found' . PHP_EOL;
	exit();
}

foreach ($list->items as $media) {

	// Try and get the media
	$agnostic_file_url = preg_replace('/^https?:\/\//', '//', $media->url, 1);
	$agnostic_uploads_url = preg_replace('/^https?:\/\//', '//', UNL_MediaHub_Controller::getURL() . 'uploads/', 1);

	if (strpos($agnostic_file_url, $agnostic_uploads_url) !== 0) {
		//not local media
		continue;
	}

	if (!$media->getLocalFileName()) {
		echo $media->id . ' is broken ' . PHP_EOL;
		echo "\t" . $media->url . PHP_EOL;
	}
}
