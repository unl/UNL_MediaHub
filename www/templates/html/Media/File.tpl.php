<?php
/* @var $context UNL_MediaHub_Media_File */
header('Location: '.$context->getMediaURL());
header('Content-Type: '.$context->type);
exit();