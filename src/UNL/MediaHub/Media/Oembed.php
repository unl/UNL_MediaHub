<?php
class UNL_MediaHub_Media_Oembed extends UNL_MediaHub_Media
{
    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    /**
     * @var int
     */
    public $format = 'json';

    /**
     * @var array
     */
    public $options = array();

    function __construct(UNL_MediaHub_Media $media = null, $options = array())
    {

        $this->media = $media;

        if (isset($options['format'])) {
            $this->format = $options['format'];
        }

        $this->options += $options;
    }

    function append_media_details(&$details, $context, $savvy) {
        $typeParts = explode("/", $context->media->type);

        switch($typeParts[0]) {
            case 'video':
                $details['type'] = 'video';
                $details['html'] = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->media->id, UNL_MediaHub_Controller::$current_embed_version));
                $details['width'] = 600;
                $details['height'] = 500;
                break;

            case 'img':
                $details['type'] = 'photo';
                $details['url'] = $context->getMediaURL();
                $details['width'] = 600;
                $details['height'] = 500;
                break;

            case 'audio':
                $details['type'] = 'rich';
                $details['html'] = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->media->id, UNL_MediaHub_Controller::$current_embed_version));
                $details['width'] = 600;
                $details['height'] = 500;
                break;

            case 'text':
            default:
                $details['type'] = 'link';
                break;
        }

    }

    function append_thumbnail_details(&$details, $context) {
        $imageSizes = @getimagesize(UNL_MediaHub_Controller::getURL($context).'/image');
        if (is_array($imageSizes)) {
            $details['thumbnail_url'] = UNL_MediaHub_Controller::getURL($context).'/image';
            $details['thumbnail_width'] =  $imageSizes[0];
            $details['thumbnail_height'] =  $imageSizes[1];
        }
    }

    /**
     * Get by URL
     *
     * @param int $url The url of the media to get
     *
     * @param string $format
     * @param array $optionsds
     * @return UNL_MediaHub_Media_Oembed
     */
    static function getByURL($url, $options = array())
    {
        $file = @file_get_contents($url . '?format=json');
        if ($file !== FALSE) {
            $mediaJSON = json_decode($file);
            if (isset($mediaJSON->id)) {
                return new self(UNL_MediaHub_Media::getByID($mediaJSON->id), $options);
            }
        }
    }
}
