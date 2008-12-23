<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$this->title.'" href="?format=xml" />');
?>
<h1><?php echo $this->feed->title; ?></h1>
<p><?php echo $this->feed->description; ?></p>
<?php
UNL_MediaYak_OutputController::display($this->media_list);
?>