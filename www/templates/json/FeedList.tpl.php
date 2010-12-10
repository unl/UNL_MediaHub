<?php
$feeds = array();
?>
{
<?php 
foreach ($context->items as $feed) {
    $feeds[] = '"'.UNL_MediaYak_Controller::getURL($feed).'":"'.$feed->title.'"';
}
$ws = array("\r\n", "\n", "\r", "    ");
echo implode(",\n", str_replace($ws, '', $feeds));
?>

}
