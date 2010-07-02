<?php
$feed_url = htmlentities(UNL_MediaYak_Controller::getURL($context), ENT_QUOTES);
?>
<h1><?php echo $context->title; ?></h1>
<p><?php echo $context->description; ?></p>
<ul>
    <li><a href="<?php echo $feed_url; ?>" class="external">Web Address:</a> <input type="text" size="75" name="feed_address" value="<?php echo $feed_url; ?>" onclick="this.select();" /></li>
    <li><a href="<?php echo $feed_url; ?>?format=xml" class="feed-icon">RSS Address:</a> <input type="text" size="75" name="feed_address" value="<?php echo $feed_url; ?>?format=xml" onclick="this.select();" /></li>
</ul>
<p class="action"><a class="feed_details" href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo $context->id; ?>">Edit Channel Details</a></p>
<p class="action"><a class="feed_users" href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo $context->id; ?>">Edit Channel Users</a></p>