<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <title><?php echo $this->title; ?></title>
    <link><?php echo UNL_MediaYak_Controller::getURL(); ?></link>
    <description><?php echo $this->description; ?></description>
    <language>en-us</language>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    <docs>http://www.rssboard.org/rss-specification</docs>
    <generator>UNL_MediaYak 0.1.0</generator>
    <managingEditor>editor@example.com</managingEditor>
    <webMaster>webmaster@example.com</webMaster>
    <ttl>5</ttl>
<?php
        if (count($this->UNL_MediaYak_Media)) {
            foreach ($this->UNL_MediaYak_Media as $media) { ?>
    <item>
      <title><?php echo $media['title']; ?></title>
      <link><?php echo $media['url']; ?></link>
      <description><?php echo $media['description']; ?></description>
      <pubDate><?php echo date('r', $media['pubDate']); ?></pubDate>
      <guid><?php echo $media['url']; ?></guid>
    </item>
<?php
            }
        }
    ?>
    </channel>
</rss>