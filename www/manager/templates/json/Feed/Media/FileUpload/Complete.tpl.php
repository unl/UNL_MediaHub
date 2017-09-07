<?php

$url = $context->options['url'];
$localUrl = UNL_MediaHub_Media::getLocalFileNameByURL($url);
$projection = UNL_MediaHub::checkMetadataProjection($localUrl);

echo json_encode(array(
    'result' => 'complete',
    'url'    => $url,
    'projection'    => $projection
));
