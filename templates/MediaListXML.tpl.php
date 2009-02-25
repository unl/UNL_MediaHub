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
      <?php
        foreach (array('UNL_MediaYak_Feed_Media_NamespacedElements_itunes',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_mrss') as $ns_class) {
            foreach ($media->$ns_class as $namespaced_element) {
                $element = "{$namespaced_element['xmlns']}:{$namespaced_element['element']}";
                echo "<$element>{$namespaced_element['value']}</$element>\n";
            }
        }
      ?>
      <enclosure url="<?php echo $media->url; ?>" length="<?php echo $media->length; ?>" type="<?php echo $media->type; ?>" />
    </item>
<?php
    }
}
?>