<?php
$items = array();
?>
{
<?php 
foreach ($context->items as $media) {
    $items[] = '"'.UNL_MediaYak_Controller::getURL($media).'":'.$savvy->render($media);
}
$ws = array("\r\n", "\n", "\r", "    ");
echo implode(",\n", str_replace($ws, '', $items));
?>

}