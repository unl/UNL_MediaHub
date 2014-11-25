<?php

class UNL_MediaHub_Feed_Media_FeedSelection
{
    /**
     * @var
     */
    protected $user;

    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    function __construct(UNL_MediaHub_User $user, UNL_MediaHub_Media $media = null)
    {
        $this->user  = $user;
        $this->media = $media;
    }

    /**
     * Get Feed Selection Data
     * 
     * Will combine available user feeds with currently selected feeds.
     *
     * @return array
     */
    public function getFeedSelectionData()
    {
        $selection_data = array();

        if ($this->media) {
            $current_feeds = $this->media->getFeeds();
            $current_feeds->run();
            //Add currently selected feeds
            foreach ($current_feeds->items as $feed) {
                $selection_data[$feed->id] = array(
                    'feed' => $feed,
                    'readonly' => true,
                    'selected' => true,
                );
            }
        }
        
        //Add feeds that the user can select
        $user_feeds = $this->user->getFeeds(array('limit'=>null));
        $user_feeds->run();
        foreach ($user_feeds->items as $feed) {
            if (isset($selection_data[$feed->id])) {
                //Already selected.
                $selection_data[$feed->id]['readonly'] = false;
                continue;
            }
            $selection_data[$feed->id] = array(
                'feed' => $feed,
                'readonly' => false,
                'selected' => false,
            );
        }
        
        return $selection_data;
    }
}