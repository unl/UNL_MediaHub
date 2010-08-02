<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$context->feed->title.'" href="?format=xml" />');
$feed_url = htmlentities(UNL_MediaYak_Controller::getURL($context->feed), ENT_QUOTES);
?>
<div id="channelIntro" class="clear">
    <img src="<?php echo $feed_url; ?>/image" alt="<?php echo $context->feed->title; ?> Image" />
    <h1><?php echo $context->feed->title; ?></h1>
    <h6 class="subhead">This channel was created by <a href="http://peoplefinder.unl.edu/?uid=<?php echo $context->feed->uidcreated?>" class="wdnPeoplefinder" title="<?php echo UNL_Services_Peoplefinder::getFullName($context->feed->uidcreated);?>'s Peoplefinder Record"><?php echo UNL_Services_Peoplefinder::getFullName($context->feed->uidcreated);?></a> on <?php echo date("F j, Y, g:i a", strtotime($context->feed->datecreated));?>.</h6>
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