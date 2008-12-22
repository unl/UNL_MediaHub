<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$this->title.'" href="?format=xml" />');
?>
<h1><?php echo $this->title; ?></h1>
<?php echo $this->description; ?>