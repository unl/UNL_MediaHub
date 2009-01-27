<?php
$feed_url = UNL_MediaYak_Controller::getURL().'channels/'.urlencode($this->title);
?>
<h1><?php echo $this->title; ?></h1>
<p><?php echo $this->description; ?></p>
<p>
Address: <a href="<?php echo $feed_url; ?>"><?php echo $feed_url; ?></a> <a href="<?php echo $feed_url; ?>?format=xml" class="feed-icon"></a>
</p>
<span class="caption"><a href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo $this->id; ?>">Edit details</a></span>
<span class="caption"><a href="<?php echo UNL_MediaYak_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo $this->id; ?>">Edit users</a></span>