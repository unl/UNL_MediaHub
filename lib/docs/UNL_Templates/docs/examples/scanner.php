<?php
highlight_file(__FILE__);
require_once 'UNL/Templates/Scanner.php';

$html = file_get_contents('http://www.unl.edu/ucomm/unltoday/');

// Scan this rendered UNL template-based page for editable regions
$scanner = new UNL_Templates_Scanner($html);

// All editable regions are now member variables of the scanner object.
echo $scanner->maincontentarea;
