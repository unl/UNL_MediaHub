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
                $details['html'] = $this->getEmbedHTML($context);
                $height = 529;
                $width = 940;
                $dimensions = $context->media->getVideoDimensions(TRUE);
                if (is_array($dimensions)) {
                    $width = $dimensions['width'];
                    $height = $dimensions['height'];
                }
                $details['width'] = $width;
                $details['height'] = $height;
                break;

            case 'img':
                $details['type'] = 'photo';
                $details['url'] = $context->getMediaURL();
                $details['width'] = 600;
                $details['height'] = 500;
                break;

            case 'audio':
                $details['type'] = 'rich';
                $details['html'] = $this->getEmbedHTML($context);
                $details['width'] = 600;
                $details['height'] = 337;
                break;

            case 'text':
            default:
                $details['type'] = 'link';
                break;
        }

    }

    function getEmbedHTML($context) {
        $prefix = 'Video Player: ';
        $size = '';
        if (!$context->media->isVideo()) {
            $prefix = 'Audio Player: ';
            $size = 'width="600" height="337"';
        }
        return '<iframe src="' . UNL_MediaHub_Controller::getURL($context) . '?format=iframe&autoplay=0" title="' . $prefix . ' ' . UNL_MediaHub::escape($context->media->title) . '" ' . $size . ' allowfullscreen frameborder="0"></iframe>';
    }

    function append_thumbnail_details(&$details, $context) {
        $imagePath = $this->media->getThumbnailURL();
        $imageSizes = @getimagesize($imagePath);
        if (is_array($imageSizes)) {
            $details['thumbnail_url'] = $imagePath;
            $details['thumbnail_width'] =  $imageSizes[0];
            $details['thumbnail_height'] =  $imageSizes[1];
        }
    }

    function getImageExtension($image) {
        switch (@exif_imagetype($image)) {
            case IMAGETYPE_GIF:
                return '.gif';
            case IMAGETYPE_JPEG:
                return '.jpg';
            case IMAGETYPE_PNG:
                return '.png';
            default:
                return '';
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
