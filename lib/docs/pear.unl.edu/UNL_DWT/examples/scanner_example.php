<?php

set_include_path(dirname(__DIR__).'/../src');

require_once 'UNL/DWT/Scanner.php';

$file = file_get_contents(dirname(__FILE__).'/'.'template_style1.dwt');

$scanned = new UNL_DWT_Scanner($file);

echo $scanned->leftnav;
echo $scanned->content;

?>