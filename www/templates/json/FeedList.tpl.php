<?php
$feeds = array();
?>
{
<?php 
foreach ($context->items as $feed) {
    $feeds[] = '"'.UNL_MediaHub_Controller::getURL($feed).'":'.$savvy->render($feed);
}
$ws = array("\r\n", "\n", "\r", "    ");
echo implode(",\n", str_replace($ws, '', $feeds));
?>

}
