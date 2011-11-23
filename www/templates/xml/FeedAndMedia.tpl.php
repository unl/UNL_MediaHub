<?php
$context->feed->loadReference('UNL_MediaHub_Feed_NamespacedElements_itunes');
$context->feed->loadReference('UNL_MediaHub_Feed_NamespacedElements_media');
echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;

?>
<rss version="2.0" <?php echo UNL_MediaHub_Controller::getNamespaceDefinationString();?>>
  <channel>
    <title><?php echo $context->feed->title; ?></title>
    <link><?php echo UNL_MediaHub_Controller::getURL($context->feed); ?></link>
    <description><?php echo $context->feed->description; ?></description>
    <language>en-us</language>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    <docs>http://www.rssboard.org/rss-specification</docs>
    <generator>UNL_MediaHub 0.1.0</generator>
    <?php
    if (!($editor = $context->feed->getEditorEmail())) {
        $editor = 'unlwdn@gmail.com';
    } ?>
    <managingEditor><?php echo $editor; ?></managingEditor>
    <webMaster>brett.bieber@gmail.com (Brett Bieber)</webMaster>
    <ttl>5</ttl>
    <?php if ($context->feed->hasImage()) :
    switch ($context->feed->image_type) {
        case 'image/png':
            $ext = '.png';
            break;
        case 'image/jpeg';
            $ext = '.jpg';
            break;
        default:
            $ext = '';
            break;
    }
    ?>
    <itunes:image href="<?php echo UNL_MediaHub_Controller::getURL($context->feed); ?>/image<?php echo $ext; ?>" />
    <image>
        <url><?php echo UNL_MediaHub_Controller::getURL($context->feed); ?>/image</url>
        <title><?php echo htmlspecialchars($context->feed->image_title, ENT_QUOTES); ?></title>
        <link><?php echo UNL_MediaHub_Controller::getURL($context->feed); ?></link>
        <?php if (isset($context->feed->image_description)): ?>
            <description><?php echo htmlspecialchars($context->feed->image_description); ?></description>
        <?php endif; ?>
    </image>
    <?php endif;
    foreach (array('UNL_MediaHub_Feed_NamespacedElements_itunes',
                   'UNL_MediaHub_Feed_NamespacedElements_media',
                   'UNL_MediaHub_Feed_NamespacedElements_boxee') as $ns_class) {
        foreach ($context->feed->$ns_class as $namespaced_element) {
            $element = "{$namespaced_element['xmlns']}:{$namespaced_element['element']}";
            switch ($element) {
                case 'itunes:category':
                    // Handle this field special
                    if (!empty($namespaced_element['attributes'])
                        && isset($namespaced_element['attributes']['text'])) {
                        $categories = array();
                        foreach ($namespaced_element['attributes']['text'] as $value) {
                            $value = explode(':', $value);
                            if (!isset($categories[$value[0]])) {
                                $categories[$value[0]] = array();
                            }
                            if (isset($value[1])) {
                                $categories[$value[0]][] = $value[1];
                            }
                        }
                        foreach ($categories as $category=>$subcategories) {
                            echo '    <itunes:category text="'.htmlspecialchars($category).'">';
                            foreach ($subcategories as $subcategory) {
                                echo PHP_EOL.'        <itunes:category text="'.htmlspecialchars($subcategory).'"></itunes:category>';
                            }
                            echo '</itunes:category>'.PHP_EOL;
                        }
                    }
                    break;
                case 'itunes:owner':
                    // We were only collecting one value before, fake it to fix validation
                    echo '
    <itunes:owner>
        <itunes:name>'.htmlspecialchars($namespaced_element['value']).'</itunes:name>
        <itunes:email>unlwdn@gmail.com</itunes:email>
    </itunes:owner>
                    ';
                    break;
                default:
                    $attribute_string = '';
                    if (!empty($namespaced_element['attributes'])) {
                        foreach ($namespaced_element['attributes'] as $attribute=>$value) {
                            $attribute_string .= " $attribute=\"$value\"";
                        }
                    }
                    echo "<{$element}{$attribute_string}>".htmlspecialchars($namespaced_element['value'])."</$element>\n";
                    break;
            }
        }
    }
    echo $savvy->render($context->media_list);
    ?>
    </channel>
</rss>

