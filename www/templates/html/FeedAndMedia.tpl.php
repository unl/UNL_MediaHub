<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$context->feed->title.'" href="?format=xml" />');
?>
<div id="channelIntro" class="clear">
    <img src="<?php ?>" /><?php //<-- @todo Brett- can you add the image to the src ? ?>
    <h1><?php echo $context->feed->title; ?></h1>
    <p><?php echo $context->feed->description; ?></p>
    <?php //<-- @todo Brett- can we dynamically create this section ? ?>
    <h6 class="list_header">This channel is also available in:</h6>
    <ul>
       <li><img src="../manager/templates/css/images/iconItunes.png" alt="Available in iTunesU" /></li>
       <li><img src="../manager/templates/css/images/iconBoxee.png" alt="Available in Boxee" /></li>
    </ul>
</div>
<?php
echo $savvy->render($context->media_list);
?>