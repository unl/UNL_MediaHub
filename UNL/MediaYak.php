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
        $return = $media->save();
        if ($return) {
            return $media;
        }
        return $return;
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
        foreach ($harvester as $url=>$media) {
            
            if (!($media instanceof UNL_MediaYak_HarvestedMedia)) {
                throw new Exception('Harvesters must return a UNL_MediaYak_HarvestedMedia class!');
            }
            
            // Try and find an existing one with this URL.
            $query    = new Doctrine_Query();
            $query->from('UNL_MediaYak_Media m');
            $query->where('m.url LIKE ?', array($media->getURL()));
            $results = $query->execute();
            if (count($results) == 1) {
                // Already exists do update
                if ($results[0]->title.$results[0]->description != $media->getTitle().$media->getDescription()
                    || strtotime($results[0]->datecreated) != strtotime($media->getDatePublished())) {
                    $results[0]->title       = $media->getTitle();
                    $results[0]->description = $media->getDescription();
                    $results[0]->datecreated = $media->getDatePublished();
                    $results[0]->save();
                    echo $results[0]->title.' has been updated!'.PHP_EOL;
                }
            } else {
                $data = array('url'         => $media->getURL(),
                              'title'       => $media->getTitle(),
                              'description' => $media->getDescription(),
                              'datecreated' => $media->getDatePublished());
                $this->addMedia($data);
                echo 'Added '.$media->getTitle().PHP_EOL;
            }
        }
    }
}