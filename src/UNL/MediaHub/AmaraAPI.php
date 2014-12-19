<?php
class UNL_MediaHub_AmaraAPI
{
    public static $amara_username = false;
    public static $amara_api_key  = false;
    
    public function __construct()
    {
        
    }
    
    protected function getStreamContext()
    {
        $options = array();
        
        $options['http'] = array(
            'timeout' => 2,
        );

        if (self::$amara_username && self::$amara_api_key) {
            $options['header'] = "X-api-username: " . self::$amara_username . "\r\n" .
                "X-apikey: " . self::$amara_api_key . "\r\n";
        }
        
        return stream_context_create($options);
    }
    
    public function request($request_path)
    {
        return @file_get_contents('http://www.amara.org/api2/partners/' . $request_path, false, $this->getStreamContext());
    }
    
    public function getMediaDetails($media_url)
    {
        if (!$info_json = $this->request('videos/?video_url=' . $media_url . '&format=json')) {
            return false;
        }
        
        return json_decode($info_json);
    }
    
    public function getTextTrack($media_url, $format = 'srt')
    {
        $media_details = $this->getMediaDetails($media_url);
        
        if (!$media_details) {
            return false;
        }
        
        if ($media_details->meta->total_count == 0) {
            return false;
        }

        return $this->request('videos/' . $media_details->objects[0]->id . '/languages/en/subtitles/?format='.$format);
    }
}