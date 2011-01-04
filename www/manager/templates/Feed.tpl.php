<?php
$feed_url = htmlentities(UNL_MediaYak_Controller::getURL($context), ENT_QUOTES);

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
<div class="three_col left">
    <form action="#" class="zenform neutral" style="background:none;-webkit-box-shadow:none;">
    <fieldset>
        <ol>
            <li style="padding-left:0px;">
              <label>
                  <a href="<?php echo $feed_url; ?>" class="external">Web Address:</a>
              </label> 
              <input type="text" size="75" name="feed_address" value="<?php echo $feed_url; ?>" onclick="this.select();" /></li>
            <li style="padding-left:0px;">
              <label>
                  <a href="<?php echo $feed_url; ?>?format=xml" class="feed-icon">RSS Address:</a>
              </label>
              <input type="text" size="75" name="feed_address" value="<?php echo $feed_url; ?>?format=xml" onclick="this.select();" /></li>
        </ol>
    </fieldset>
    </form>
</div>
<div class="one_col right">
    <div class="actionItems" style="margin-top:35px;">
        <a class="action edit details" href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo $context->id; ?>">Edit Channel Details</a>
        <a class="action edit users" href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo $context->id; ?>">Edit Channel Users</a>
    </div>
</div>
<div class="clear"></div>