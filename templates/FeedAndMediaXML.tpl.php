<?php
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_MediaList', 'MediaListXML');
?>
<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <title><?php echo $this->feed->title; ?></title>
    <link><?php echo UNL_MediaYak_Controller::getURL(); ?></link>
    <description><?php echo $this->feed->description; ?></description>
    <language>en-us</language>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    <docs>http://www.rssboard.org/rss-specification</docs>
    <generator>UNL_MediaYak 0.1.0</generator>
    <managingEditor>editor@example.com</managingEditor>
    <webMaster>brett.bieber@gmail.com</webMaster>
    <ttl>5</ttl>
    <?php
    UNL_MediaYak_OutputController::display($this->media_list);
    ?>
    </channel>
</rss>