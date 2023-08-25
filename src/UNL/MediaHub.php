<?php
class UNL_MediaHub
{
    public static $dsn;
    
    public static $ffmpeg_path;

    public static $ffprobe_path;
    
    public static $mediainfo_path;

    public static $rude_shell;
    
    public static $admins = array();
    public static $auto_transcode_hls_users = array();
    public static $auto_transcode_hls_all_users = true;
    public static $auto_transcode_pro_all_users = true;
    
    //The bucket name (not full ARN)
    public static $transcode_input_bucket = '';
    public static $transcode_output_bucket = '';
    public static $transcode_mediaconvert_api_endpoint = '';
    public static $transcode_mediaconvert_role = '';
    
    public static $verbose_errors = false;
    
    function __construct()
    {
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
        Doctrine::loadModels(dirname(dirname(__FILE__)).'/UNL/MediaHub/Media');
        Doctrine_Manager::connection(self::$dsn);
    }

    /**
     * 
     * @return Doctrine_Connection
     */
    function getDB()
    {
    	return Doctrine_manager::getInstance()->getCurrentConnection();
    }

    public static function registerAutoloaders()
    {
        include_once 'Doctrine.php';
        require_once __DIR__ . '/../../vendor/autoload.php';
        spl_autoload_register(array('Doctrine', 'autoload'));
    }
    
    /**
     * Add media to the mediahub
     *
     * @param array $details
     *
     * @return UNL_MediaHub_Media
     */
    function addMedia(array $details)
    {
        $media = new UNL_MediaHub_Media();
        $media->fromArray($details);
        $media->save();
        return $media;
    }
    
    /**
     * Add media from a Harvester.
     *
     * @param UNL_MediaHub_Harvester $harvester A harvester that can returned harvested media.
     *
     * @return bool
     */
    function harvest(UNL_MediaHub_Harvester $harvester)
    {
        foreach ($harvester as $url=>$harvested_media) {
            
            if (!($harvested_media instanceof UNL_MediaHub_HarvestedMedia)) {
                throw new Exception('Harvesters must return a UNL_MediaHub_HarvestedMedia class!');
            }
            
            // Collect the namespaced elements first
            $namespacedElements = $harvested_media->getNamespacedElements();
            $add_ns_elements = array(); 
            if (count($namespacedElements)) {
                foreach ($namespacedElements as $xmlns=>$elements) {
                    switch($xmlns) {
                        case 'media':
                        case 'unl':
                        case 'itunes':
                        case 'itunesu':
                            $ns_class = 'UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns;
                            foreach ($elements as $element=>$value) {
                                $add_ns_elements[$ns_class][] = array('element'=>$element, 'value'=>$value);
                            }
                            break;
                        default:
                            //echo 'Unknown namespaced attribute '.$xmlns.':'.$element.PHP_EOL;
                            break;
                    }
                }
            }
            
            // Try and find an existing one with this URL.
            if ($media = UNL_MediaHub_Media::getByURL($harvested_media->getURL())) {
                // Already exists do update
                $media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_itunesu');
                $media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_itunes');
                $media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_media');
                $media->loadReference('UNL_MediaHub_Feed_Media_NamespacedElements_boxee');
                $media->title       = $harvested_media->getTitle();
                $media->description = $harvested_media->getDescription();
                $media->datecreated = $harvested_media->getDatePublished();
                
                
                $media->UNL_MediaHub_Feed_Media_NamespacedElements_itunes->delete();
                //$media->UNL_MediaHub_Feed_Media_NamespacedElements_media->delete();
                $media->save();
                $media->synchronizeWithArray(array_merge($media->toArray(), $add_ns_elements));
                $media->save();
                //echo $media->title.' has been updated!'.PHP_EOL;
            } else {
                $data = array('url'         => $harvested_media->getURL(),
                              'title'       => $harvested_media->getTitle(),
                              'description' => $harvested_media->getDescription(),
                              'datecreated' => $harvested_media->getDatePublished());
                $media = $this->addMedia($data);
                $media->synchronizeWithArray(array_merge($media->toArray(), $add_ns_elements));
                $media->save();
                echo 'Added '.$media->title.PHP_EOL;
            }
        }

    }

    /**
     * Check if this media is a video file.
     *
     * @param string URL or content-type
     *
     * @return bool
     */
    public static function isVideo($media)
    {
        if (filter_var($media, FILTER_VALIDATE_URL)) {
            // This is a URL, use file extension to check
            switch (pathinfo(strtolower($media), PATHINFO_EXTENSION)) {
                case 'mp3':
                    return false;
                default:
                    return true;
            }
        }
    
        if (strpos($media, 'video') === 0) {
            return true;
        }
        return false;
    }

    /**
     * @param string $url the url to redirect to
     */
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit();
    }

    /**
     * Get the absolute path for the root directory of the project
     * 
     * @return string
     */
    public static function getRootDir()
    {
        return dirname(dirname(__DIR__));
    }

    /**
     * Get the path (as confugired) to the ffmpeg executable
     * 
     * @return string
     */
    public static function getFfmpegPath() {
        $ffmpeg = UNL_MediaHub::getRootDir() . '/ffmpeg/ffmpeg';
        if (self::$ffmpeg_path) {
            $ffmpeg = self::$ffmpeg_path;
        }
        if(!self::$rude_shell){
          $ffmpeg = "nice ".$ffmpeg;
        }
        
        return $ffmpeg;
    }

    /**
     * Get the path (as confugired) to the ffprobe executable
     * 
     * @return string
     */
    public static function getFfprobePath() {
        $ffprobe = UNL_MediaHub::getRootDir() . '/ffmpeg/ffprobe';
        if (self::$ffprobe_path) {
            $ffprobe = self::$ffprobe_path;
        }
        if(!self::$rude_shell){
          $ffprobe = "nice ".$ffprobe;
        }
        
        return $ffprobe;
    }

    /**
     * @return \Mhor\MediaInfo\MediaInfo
     */
    public static function getMediaInfo()
    {
        $mediaInfo = new \Mhor\MediaInfo\MediaInfo();
        if (version_compare(phpversion(), '7.0', '>')) {
            $mediaInfo->setConfig('use_oldxml_mediainfo_output_format', true);
        }

        if (self::$mediainfo_path) {
            $mediaInfo->setConfig('command', self::$mediainfo_path);
        }
        
        return $mediaInfo;
    }


        /**
     * Set the Media RSS, projection element
     * 
     * @return true
     */
    public static function checkMetadataProjection($url)
    {

        if(!isset($url)){
            return;
        }

        $return = array();
        $status = 1;

        $url = escapeshellarg($url);

        exec(UNL_MediaHub::getFfprobePath() . " -v quiet -print_format json -show_format -show_streams $url", $return, $status);

        $json = "";

        $length = sizeof($return);
        for ($i=0; $i < $length; $i++) { 
            $json.=$return[$i];
        }

        $metadata = json_decode($json);
        
        if (!$metadata || !isset($metadata->streams) || !is_countable($metadata->streams)) {
            return false;
        }

        $projection = false;
        $length = count($metadata->streams);
        for ($i=0; $i < $length; $i++) { 
            if(isset($metadata->streams[$i]->side_data_list)){
                $side_data_list_length = sizeof($metadata->streams[$i]->side_data_list);
                for ($a=0; $a < $side_data_list_length; $a++) { 
                    if(isset($metadata->streams[$i]->side_data_list[$a]->side_data_type)){
                        if($metadata->streams[$i]->side_data_list[$a]->side_data_type == 'Spherical Mapping'){
                            $projection = $metadata->streams[$i]->side_data_list[$a]->projection;
                        }
                    }
                }

                
            }
        }

        return $projection;

    }

    /**
     * Escape an HTML string
     * 
     * @param $html
     * @return string
     */
    public static function escape($html)
    {
        return htmlspecialchars($html, ENT_QUOTES, 'UTF-8', false);
    }
}