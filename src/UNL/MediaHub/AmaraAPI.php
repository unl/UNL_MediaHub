<?php
class UNL_MediaHub_AmaraAPI
{
    const BASE_API_URI = 'https://amara.org/api/';

    public static $amaraAPIKey  = false;

    /**
     * API version we are using
     *
     * @var string
     */
    protected $amaraFuture = "20190619";

    /**
     * Number of seconds until timeout for requests.
     *
     * @var int
     */
    protected $timeout = 5;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * @param string $requestPath the path and query string parameters after the base API endpoint
     * @return string
     */
    public function get($requestPath)
    {
        try {
            $response = $this->guzzle->get(self::BASE_API_URI . $requestPath, array(
                'headers' => array(
                    'X-api-key'       => self::$amaraAPIKey,
                    'X-API-FUTURE'   => $this->amaraFuture,
                ),
                'timeout' => $this->timeout
            ));

            return $response->getBody();
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        }
    }

    public function post($requestPath, $content)
    {
        try {
            $response = $this->guzzle->post(self::BASE_API_URI.$requestPath, array(
                'headers' => array(
                    'X-api-key'       => self::$amaraAPIKey,
                    'X-API-FUTURE'   => $this->amaraFuture,
                ),
                'body' => json_encode($content),
                'timeout' => $this->timeout,
            ));
            return $response->getBody();
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        }
    }

    /**
     * @param string $mediaUrl the full media URL
     * @return bool|mixed
     */
    public function getMediaDetails($mediaUrl)
    {
        if (!$infoJSON = $this->get('videos/?video_url=' . $mediaUrl . '&format=json')) {
            return false;
        }

        return json_decode($infoJSON);
    }
    
    public function createMedia($mediaUrl)
    {
        return $this->post('videos/?format=json', array(
            'video_url' => $mediaUrl
        ));
    }

    /**
     * @param string $mediaUrl the full media URL
     * @return bool|string
     */
    public function getCaptionEditURL($mediaUrl)
    {
        $mediaDetails = $this->getMediaDetails($mediaUrl);

        if (!$mediaDetails || $mediaDetails->meta->total_count == 0) {
            //create the media
            if (!$result = $this->createMedia($mediaUrl)) {
                //Media could not be created, can not continue.
                return false;
            }
            
            //update the details
            $mediaDetails = $this->getMediaDetails($mediaUrl);
        }
        return 'https://amara.org/en/videos/' . $mediaDetails->objects[0]->id . '/info';
    }

    /**
     * @param string $mediaUrl the full media URL
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getTextTrackByMediaURL($mediaUrl, $format = 'srt')
    {
        $mediaDetails = $this->getMediaDetails($mediaUrl);

        if (!$mediaDetails) {
            return false;
        }

        if ($mediaDetails->meta->total_count == 0) {
            return false;
        }

        return $this->get('videos/' . $mediaDetails->objects[0]->id . '/languages/en/subtitles/?format='.$format);
    }

    /**
     * @param string $mediaID the amara media id
     * @param string $langCode the lang code
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getTextTrackByMediaID($mediaID, $langCode, $format = 'srt')
    {
        return $this->get('videos/' . $mediaID. '/languages/' . $langCode . '/subtitles/?format='.$format);
    }

    /**
     * @param $mediaID
     * @param string $mediaUrl the full media URL
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getMediaHubTextTracks($mediaID, $mediaUrl, $format = 'srt')
    {
        $mediaDetails = $this->getMediaDetails($mediaUrl);
        $tracks = array();

        if (!$mediaDetails) {
            return $tracks;
        }

        if ($mediaDetails->meta->total_count == 0) {
            return $tracks;
        }

        foreach ($mediaDetails->objects[0]->languages as $track) {
            $tracks[$track->code] = UNL_MediaHub_Controller::$url . 
            'media/'.
            $mediaID.
            '/'.
            $format.
            '?amara_id='.
            $mediaDetails->objects[0]->id.
            '&lang_code='.
            $track->code;
        }

        return $tracks;
    }


    public function getTextTracks($mediaUrl, $format = 'vtt')
    {
        $mediaDetails = $this->getMediaDetails($mediaUrl);
        $tracks        = array();

        if (!$mediaDetails) {
            return $tracks;
        }

        if ($mediaDetails->meta->total_count == 0) {
            return $tracks;
        }

        foreach ($mediaDetails->objects[0]->languages as $track) {
            $tracks[$track->code] = (string)$this->getTextTrackByMediaID(
                $mediaDetails->objects[0]->id,
                $track->code,
                $format
            );
        }

        return $tracks;
    }
}
