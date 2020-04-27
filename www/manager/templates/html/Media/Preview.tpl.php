
<?php

$template = 'Media/Preview/Audio.tpl.php';

if ($context->isVideo()) {
    $template = 'Media/Preview/Video.tpl.php';
}

echo $savvy->render($context, $template);
