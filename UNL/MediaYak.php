<?php
class UNL_MediaYak
{
    public $dsn;
    
    function __construct($dsn)
    {
        include_once 'Doctrine/lib/Doctrine.php';

        spl_autoload_register(array('Doctrine', 'autoload'));
        
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
        Doctrine::loadModels(dirname(dirname(__FILE__)).'/UNL/MediaYak/Media');
        Doctrine_Manager::connection($dsn);
    }
    
    /**
     * Add media to the mediahub
     *
     * @param array $details
     *
     * @return UNL_MediaYak_Media
     */
    function addMedia(array $details)
    {
        $media = new UNL_MediaYak_Media();
        $media->fromArray($details);
        $media->save();
        return $media;
    }
    
    /**
     * Add media from a Harvester.
     *
     * @param UNL_MediaYak_Harvester $harvester A harvester that can returned harvested media.
     *
     * @return bool
     */
    function harvest(UNL_MediaYak_Harvester $harvester)
    {
        foreach ($harvester as $url=>$harvested_media) {
            
            if (!($harvested_media instanceof UNL_MediaYak_HarvestedMedia)) {
                throw new Exception('Harvesters must return a UNL_MediaYak_HarvestedMedia class!');
            }
            
            // Collect the namespaced elements first
            $namespacedElements = $harvested_media->getNamespacedElements();
            $add_ns_elements = array(); 
            if (count($namespacedElements)) {
                foreach ($namespacedElements as $xmlns=>$elements) {
                    switch($xmlns) {
                        case 'media':
                            $xmlns = 'media';
                        case 'unl':
                        case 'itunes':
                            $ns_class = 'UNL_MediaYak_Feed_Media_NamespacedElements_'.$xmlns;
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
            if ($media = UNL_MediaYak_Media::getByURL($harvested_media->getURL())) {
                // Already exists do update
                $media->loadReference('UNL_MediaYak_Feed_Media_NamespacedElements_itunes');
                $media->loadReference('UNL_MediaYak_Feed_Media_NamespacedElements_media');
                $media->title       = $harvested_media->getTitle();
                $media->description = $harvested_media->getDescription();
                $media->datecreated = $harvested_media->getDatePublished();
                
                
                $media->UNL_MediaYak_Feed_Media_NamespacedElements_itunes->delete();
                //$media->UNL_MediaYak_Feed_Media_NamespacedElements_media->delete();
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
}