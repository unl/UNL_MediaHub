<?php
$this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_itunes');
$this->feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_mrss');
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_MediaList', 'MediaListXML');
echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss/">
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
    <webMaster>brett.bieber@gmail.com (Brett Bieber)</webMaster>
    <ttl>5</ttl>
    <?php
    foreach (array('UNL_MediaYak_Feed_NamespacedElements_itunes',
                   'UNL_MediaYak_Feed_NamespacedElements_mrss') as $ns_class) {
        foreach ($this->feed->$ns_class as $namespaced_element) {
            $element = "{$namespaced_element['xmlns']}:{$namespaced_element['element']}";
            echo "<$element>{$namespaced_element['value']}</$element>\n";
        }
    }
    UNL_MediaYak_OutputController::display($this->media_list);
    ?>
    </channel>
</rss>