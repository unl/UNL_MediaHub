<item>
  <title><?php echo htmlspecialchars($context->title); ?></title>
  <link><?php echo htmlspecialchars($context->url); ?></link>
  <description><![CDATA[
  <?php echo $context->description; ?>
  ]]></description>
  <guid><?php echo htmlspecialchars($context->url); ?></guid>
  <pubDate><?php echo date('r', strtotime($context->datecreated)); ?></pubDate>
  <?php
    try {
        foreach (array('UNL_MediaHub_Feed_Media_NamespacedElements_itunesu',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunes',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_media',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_boxee') as $ns_class) {
            foreach ($context->$ns_class as $namespaced_element) {
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
  <enclosure url="<?php echo $context->url; ?>" length="<?php echo $context->length; ?>" type="<?php echo $context->type; ?>" />
</item>