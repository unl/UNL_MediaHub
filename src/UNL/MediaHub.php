<?php
class UNL_MediaHub
{
    public static $dsn;
    
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
}