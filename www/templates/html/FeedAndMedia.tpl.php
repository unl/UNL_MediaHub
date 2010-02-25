<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$context->feed->title.'" href="?format=xml" />');
?>
<h1><?php echo $context->feed->title; ?></h1>
<p><?php echo $context->feed->description; ?></p>
<?php
echo $savvy->render($context->media_list);
?>