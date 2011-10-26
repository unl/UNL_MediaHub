<?php
if ($context->output instanceof UNL_MediaHub_MediaList
    || (is_array($context->output) && (
        $context->output[0] instanceof UNL_MediaHub_MediaList
        || $context->output[0] instanceof UNL_MediaHub_FeedList
        || $context->output[0] instanceof UNL_MediaHub_Media
        || $context->output[0] instanceof UNL_MediaHub_DefaultHomepage))) {
 echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:itunesu="http://www.itunesu.com/feed" xmlns:media="http://search.yahoo.com/mrss/" xmlns:boxee="http://boxee.tv/spec/rss/" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">
  <channel>
    <title>UNL MediaHub</title>
    <link><?php echo UNL_MediaHub_Controller::getURL(); ?></link>
    <description>Media from the University of Nebraska-Lincoln</description>
    <language>en-us</language>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    <docs>http://www.rssboard.org/rss-specification</docs>
    <generator>UNL_MediaHub 0.1.0</generator>
    <managingEditor>brett.bieber@gmail.com (Brett Bieber)</managingEditor>
    <webMaster>brett.bieber@gmail.com (Brett Bieber)</webMaster>
    <ttl>5</ttl>
    <?php 
    echo $savvy->render($context->output);
    ?>
    </channel>
</rss>
<?php 
} else {
    echo $savvy->render($context->output);
}
