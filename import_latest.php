<?php

require_once 'UNL/Autoload.php';
require_once 'config.inc.php';

$controller = new UNL_MediaYak_Controller($dsn);

$feed_url = $all_items_feed = 'http://pipes.yahoo.com/pipes/pipe.run?_id=facba651e446f1754f729a8edd6e1083&_render=php';
$recent_items_feed = 'http://pipes.yahoo.com/pipes/pipe.run?_id=fc9a8e08fbaa48a6da49e871fbea3d24&_render=php';

$mediayak = new UNL_MediaYak($dsn);

if ($feed = @file_get_contents($feed_url)) {
    $feed = unserialize($feed);
    if ($feed) {
        foreach ($feed['value']['items'] as $entry) {
            // Try and find an existing one with this URL.
            $query    = new Doctrine_Query();
            $query->from('UNL_MediaYak_Media m');
            $query->where('m.url LIKE ?', array($entry['link']));
            $results = $query->execute();
            if (count($results)) {
                // Already exists
            } else {
                $media = array('url'         => $entry['link'],
                               'title'       => $entry['title'],
                               'description' => $entry['description'],
                               'datecreated' => date('Y-m-d H:i', $entry['y:published']['utime']));
                if (isset($entry['itunes:author'])) {
                    $media['author'] = $entry['itunes:author']; 
                }
                $mediayak->addMedia($media);
                echo 'Added '.$entry['title'].'<br />';
            }
        }
    }
}


?>