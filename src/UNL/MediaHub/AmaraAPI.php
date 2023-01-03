<?php
class UNL_MediaHub_AmaraAPI
{
    const BASE_API_URI = 'https://amara.org/api/';
    
    public static $amara_username = false;
    public static $amara_api_key  = false;

    /**
     * Number of seconds until timeout for requests.
     * 
     * @var int
     */
    protected $api_future = "20190619";

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
     * @param string $request_path the path and query string parameters after the base API endpoint
     * @return string
     */
    public function get($request_path)
    {
        try {
            $response = $this->guzzle->get(self::BASE_API_URI . $request_path, array(
                'headers' => array(
                    // 'X-api-username' => self::$amara_username,
                    'X-api-key'       => self::$amara_api_key,
                    'X-API-FUTURE'   => $this->api_future,
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
    
    public function post($request_path, $content)
    {
        try {
            $response = $this->guzzle->post(self::BASE_API_URI.$request_path, array(
                'headers' => array(
                    // 'X-api-username' => self::$amara_username,
                    'X-api-key'       => self::$amara_api_key,
                    'X-API-FUTURE'   => $this->api_future,
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
     * @param string $media_url the full media URL
     * @return bool|mixed
     */
    public function getMediaDetails($media_url)
    {
        if (!$info_json = $this->get('videos/?video_url=' . $media_url . '&format=json')) {
            return false;
        }

        return json_decode($info_json);
    }
    
    public function createMedia($media_url)
    {
        return $this->post('videos/?format=json', array(
            'video_url' => $media_url
        ));
    }

    /**
     * @param string $media_url the full media URL
     * @return bool|string
     */
    public function getCaptionEditURL($media_url)
    {
        $media_details = $this->getMediaDetails($media_url);

        if (!$media_details || $media_details->meta->total_count == 0) {
            //create the media
            if (!$result = $this->createMedia($media_url)) {
                //Media could not be created, can not continue.
                return false;
            }
            
            //update the details
            $media_details = $this->getMediaDetails($media_url);
        }
        return 'https://amara.org/en/videos/' . $media_details->objects[0]->id . '/info';
    }

    /**
     * @param string $media_url the full media URL
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getTextTrackByMediaURL($media_url, $format = 'srt')
    {
        $media_details = $this->getMediaDetails($media_url);

        if (!$media_details) {
            return false;
        }

        if ($media_details->meta->total_count == 0) {
            return false;
        }

        return $this->get('videos/' . $media_details->objects[0]->id . '/languages/en/subtitles/?format='.$format);
    }

    /**
     * @param string $media_id the amara media id
     * @param string $lang_code the lang code
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getTextTrackByMediaID($media_id, $lang_code, $format = 'srt')
    {
        return $this->get('videos/' . $media_id. '/languages/' . $lang_code . '/subtitles/?format='.$format);
    }

    /**
     * @param $media_id
     * @param string $media_url the full media URL
     * @param string $format the format for the text track (srt or vtt)
     * @return bool|string
     */
    public function getMediaHubTextTracks($media_id, $media_url, $format = 'srt')
    {
        $media_details = $this->getMediaDetails($media_url);
        $tracks = array();

        if (!$media_details) {
            return $tracks;
        }

        if ($media_details->meta->total_count == 0) {
            return $tracks;
        }

        foreach($media_details->objects[0]->languages as $track) {
            $tracks[$track->code] = UNL_MediaHub_Controller::$url . 'media/'.$media_id.'/'.$format.'?amara_id='.$media_details->objects[0]->id.'&lang_code='.$track->code;
        }

        return $tracks;
    }


    public function getTextTracks($media_url, $format = 'vtt')
    {
        $media_details = $this->getMediaDetails($media_url);
        $tracks        = array();

        if (!$media_details) {
            return $tracks;
        }

        if ($media_details->meta->total_count == 0) {
            return $tracks;
        }

        foreach($media_details->objects[0]->languages as $track) {
            $tracks[$track->code] = (string)$this->getTextTrackByMediaID($media_details->objects[0]->id, $track->code, $format);
        }

        return $tracks;
    }
}
