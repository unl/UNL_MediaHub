<?php

$template = 'Media/Preview/Audio.tpl.php';

if (UNL_MediaHub_Media::isVideo($context->url)) {
    $template = 'Media/Preview/Video.tpl.php';
}

echo $savvy->render($context, $template);
?>