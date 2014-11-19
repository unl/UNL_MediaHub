<?php
$feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context), ENT_QUOTES);

?>
<div id="channelIntro">
    <img src="<?php echo $feed_url; ?>/image" alt="" />
    <h1><?php echo $context->title; ?></h1>
    <p><?php echo $context->description; ?></p>
    <?php //<-- @todo Brett- can we dynamically create this section ? ?>
    <!--
    <h6 class="list_header">This channel is also available in:</h6>
    <ul>
       <li><img src="templates/css/images/iconItunes.png" alt="Available in iTunesU" /></li>
       <li><img src="templates/css/images/iconBoxee.png" alt="Available in Boxee" /></li>
    </ul>
      -->
    <p>This channel was created by <a href="http://peoplefinder.unl.edu/?uid=<?php echo $context->uidcreated?>" class="wdnPeoplefinder" title="<?php echo @UNL_Services_Peoplefinder::getFullName($context->uidcreated);?>'s Peoplefinder Record"><?php echo @UNL_Services_Peoplefinder::getFullName($context->uidcreated);?></a> on <?php echo date("F j, Y, g:i a", strtotime($context->datecreated));?>.</p>
</div>

<div class="grid3">
    
</div>
<div class="clear"></div>