<?php
if (count($this->items)) {
    foreach ($this->items as $media) { ?>
    <item>
      <title><?php echo htmlspecialchars($media->title); ?></title>
      <link><?php echo htmlspecialchars($media->url); ?></link>
      <description><![CDATA[
      <?php echo $media->description; ?>
      ]]></description>
      <pubDate><?php echo date('r', strtotime($media->datecreated)); ?></pubDate>
      <guid><?php echo $media->url; ?></guid>
      <?php
        try {
            foreach (array('UNL_MediaYak_Feed_Media_NamespacedElements_itunes',
                           'UNL_MediaYak_Feed_Media_NamespacedElements_mrss') as $ns_class) {
                foreach ($media->$ns_class as $namespaced_element) {
                    $element = "{$namespaced_element['xmlns']}:{$namespaced_element['element']}";
                    $attribute_string = '';
                    if (!empty($namespaced_element['attributes'])) {
                        foreach ($namespaced_element['attributes'] as $attribute=>$value) {
                            $attribute_string .= " $attribute=\"$value\"";
                        }
                    }
                    echo "<{$element}{$attribute_string}>".htmlspecialchars($namespaced_element['value'])."</$element>\n";
                }
            }
        } catch (Exception $e) {
            // Error, just skip this for now.
        }
      ?>
      <enclosure url="<?php echo $media->url; ?>" length="<?php echo $media->length; ?>" type="<?php echo $media->type; ?>" />
    </item>
<?php
    }
}
?>