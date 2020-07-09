<?php

class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}

$details = array(
    'version' => '1.0',
    'title' => addslashes($context->media->title),
    'author_name' => addslashes($context->media->author),
    'provider_name' => 'UNL Mediahub',
    'provider_url' => UNL_MediaHub_Controller::$url
);

$context->append_thumbnail_details($details, $context);
$context->append_media_details($details, $context, $savvy);

if ($context->format == 'json') {
    echo json_encode($details);
    die();
} elseif ($context->format == 'xml') {
    $xml = new SimpleXMLExtended("<?xml version='1.0' encoding='UTF-8' standalone='yes'?><oembed></oembed>");
    foreach ($details as $key => $value) {
        if ($key == 'html') {
            $xml->html = NULL;
            $xml->html->addCData($value);
        } else {
            $xml->addChild($key, $value);
        }
    }
    echo $xml->asXML();
    die();
} else {
    http_response_code(404);
}
