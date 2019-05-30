<?php
$details = array(
    'version'   => '1.0',
    'title'     => UNL_MediaHub::escape($context->title),
    'author_name'       => UNL_MediaHub::escape($context->author),
    'provider'          => 'UNL Mediahub',
    'provider_url'      => UNL_MediaHub_Controller::$url
);
append_media_details($details, $context, $savvy);
append_thumbnail_details($details, $context);
echo json_encode($details);


function append_media_details(&$details, $context, $savvy) {
    $typeParts = explode("/", $context->type);

    switch($typeParts[0]) {
        case 'video':
            $details['type'] = 'video';
            $details['html'] = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version));
            break;

        case 'img':
            $details['type'] = 'photo';
            $details['url'] = 'photo';
            break;

        case 'audio':
            $details['type'] = 'rich';
            $details['html'] = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version));
            break;

        case 'text':
        default:
            $details['type'] = 'link';
            break;
    }

}

function append_thumbnail_details(&$details, $context) {
    $imageSizes = getimagesize(UNL_MediaHub_Controller::getURL($context).'/image');
    if (is_array($imageSizes)) {
        $details['thumbnail_url'] = UNL_MediaHub_Controller::getURL($context).'/image';
        $details['thumbnail_width'] =  $imageSizes[0];
        $details['thumbnail_height'] =  $imageSizes[1];
    }
}