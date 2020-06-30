<?php
$title = !empty($context) ? '"title": "' . $context . '", ': '';
echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_INFO, '{' . $title . '"size": 5}');
?>
