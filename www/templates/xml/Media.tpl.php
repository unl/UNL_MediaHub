<item>
  <title><?php echo htmlspecialchars($context->title); ?></title>
  <link><?php echo htmlspecialchars($context->getMediaURL()); ?></link>
  <description><![CDATA[
  <?php echo $context->description; ?>
  ]]></description>
  <guid><?php echo htmlspecialchars($context->getMediaURL()); ?></guid>
  <pubDate><?php echo date('r', strtotime($context->datecreated)); ?></pubDate>
  <?php
    try {
        foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $ns_class) {
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
  <enclosure url="<?php echo $context->getMediaURL(); ?>" length="<?php echo $context->length; ?>" type="<?php echo $context->type; ?>" />
</item>