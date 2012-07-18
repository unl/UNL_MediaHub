<?php
/* @var $context UNL_MediaHub_Media_Image */
header('Location: '.$context->getThumbnailURL());
header('Content-Type: image/jpeg');
exit();