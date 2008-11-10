<?php
class UNL_MediaYak
{
    public $dsn;
    
    function __construct($dsn)
    {
        
    }
    
    function addMedia(array $details)
    {
        $media = new UNL_MediaYak_Media();
        $media->fromArray($details);
        return $media->save();
    }
    
    /**
     * Add media from a Yahoo Pipe containing the serialized feed.
     *
     * @param string $serialized_pipe_url URL with the serialized feed.
     * 
     * @return bool
     */
    function addFromYahooPipe($serialized_pipe_url)
    {
        if ($feed = @file_get_contents($serialized_pipe_url)) {
            $feed = unserialize($feed);
            if ($feed) {
                foreach ($feed['value']['items'] as $entry) {
                    // Try and find an existing one with this URL.
                    $query    = new Doctrine_Query();
                    $query->from('UNL_MediaYak_Media m');
                    $query->where('m.url LIKE ?', array($entry['link']));
                    $results = $query->execute();
                    if (count($results)) {
                        // Already exists
                    } else {
                        $media = array('url'         => $entry['link'],
                                       'title'       => $entry['title'],
                                       'description' => $entry['description'],
                                       'datecreated' => date('Y-m-d H:i', $entry['y:published']['utime']));
                        if (isset($entry['itunes:author'])) {
                            $media['author'] = $entry['itunes:author']; 
                        }
                        $this->addMedia($media);
                        echo 'Added '.$entry['title'].'<br />';
                    }
                }
                return true;
            }
        }
        
        return false;
    }
}