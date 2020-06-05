<?php
$title = !empty($context) ? '"title": "' . $context . '", ': '';
echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_INFO_PIN, '{' . $title . '"width": 18, "height": 18, "style": "' . \UNL\Templates\Icons::STYLE_FILL_LIGHTBLUE .'"}');
?>
