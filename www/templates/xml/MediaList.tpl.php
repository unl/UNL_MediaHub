<?php
if (count($context->items)) {
    foreach ($context->items as $media) {
        echo $savvy->render($media);
    }
}
?>