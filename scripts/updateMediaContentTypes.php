<?php
require_once 'UNL/Autoload.php';
require_once dirname(__FILE__).'/../config.inc.php';

require_once 'Validate.php';

$mediayak = new UNL_MediaYak($dsn);

UNL_MediaYak_MediaList::$results_per_page = 100;
$list = new UNL_MediaYak_MediaList(new UNL_MediaYak_MediaList_Filter_NoContentType());
if (count($list->items)) {
    $validate = new Validate();
    foreach ($list->items as $media) {
        if ($validate->uri($media->url)) {
            $headers = get_headers($media->url);
            if (count($headers)) {
                foreach($headers as $header) {
                    if (strpos($header, 'Content-Type: ') !== false) {
                        $media->type = substr($header, 14);
                    }
                    if (strpos($header, 'Content-Length: ') !== false) {
                        $media->length = substr($header, 16);
                    }
                }
                $media->save();
                echo 'Saved file, '.$media->url.PHP_EOL;
            }
        } else {
            echo 'Invalid url '.$media->url.PHP_EOL;
        }
    }
}
?>