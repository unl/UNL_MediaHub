<?php
if (count($this->items)) {
    foreach ($this->items as $media) { ?>
    <item>
      <title><?php echo $media->title; ?></title>
      <link><?php echo $media->url; ?></link>
      <description><![CDATA[
      <?php echo $media->description; ?>
      ]]>
      </description>
      <pubDate><?php echo date('r', strtotime($media->datecreated)); ?></pubDate>
      <guid><?php echo $media->url; ?></guid>
    </item>
<?php
    }
}
?>