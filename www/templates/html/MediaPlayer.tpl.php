<?php
if ($context->getPlayerVersion() == 3) {
    echo $savvy->render($context, 'MediaPlayer/v3/player.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/v2/player.tpl.php');
}
