<?php
$details = array('id'          => $context->id,
                 'url'         => $context->url,
                 'title'       => $context->title,
                 'description' => $context->description,
                 'length'      => $context->length,
                 'image'       => UNL_MediaHub_Controller::getURL($context).'/image',
                 'type'        => $context->type,
                 'author'      => $context->author,
                 'link'        => UNL_MediaHub_Controller::getURL($context),
                 'pubDate'     => $context->datecreated,
                 'dateupdated' => $context->dateupdated);

$embed = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version));
$details['embed']= $embed;

try{
    foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $ns_class) {
        foreach ($context->$ns_class as $namespaced_element) {
            $element = $namespaced_element['xmlns'] . "_" . $namespaced_element['element'];
            switch ($element) {
                default:
                    $attribute_string = '';
                if (!empty($namespaced_element['attributes'])) {
                    foreach ($namespaced_element['attributes'] as $attribute=>$value) {
                        $attribute_string .= " $attribute=\"$value\"";
                    }
                }
                $details[$element . $attribute_string] = $namespaced_element['value'];
                break;
            }
        }
    }
} catch (Exception $e) {
        // Error, just skip this for now.
}

echo json_encode($details);