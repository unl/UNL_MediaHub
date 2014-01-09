<?php

set_include_path(dirname(__DIR__).'/../src');
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once 'UNL/DWT/Scanner.php';

$file = file_get_contents(dirname(__FILE__).'/basic/'.'template_style1.dwt');

$scanned = new UNL_DWT_Scanner($file);

// Modify the scanned content
$scanned->content .= '<h3>Scanned content from the left nav:</h3>';

// Also, access the content that was scanned in
$scanned->content .= '<pre>'.$scanned->leftnav.'</pre>';

echo $scanned;
