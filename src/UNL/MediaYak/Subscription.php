<?php
class UNL_MediaYak_Subscription extends UNL_MediaYak_Models_BaseSubscription
{
    /**
     * Processes this subscription and adds media not currently
     * added to the feed this subscription is for.
     *
     * @return int number of media added to feeds
     */
    public function process()
    {
        $added      = 0;
        $media_list = $this->getMediaList();
        $media_list->run();

        $feed_list  = $this->getFeedList();
        $feed_list->run();

        foreach ($media_list as $media) {
            foreach ($feed_list->items as $feed) {
                if (!$feed->hasMedia($media)) {
                    if ($feed->addMedia($media)) {
                        $added++;
                    }
                }
            }
        }

        return $added;
    }

    function getFeedList()
    {
        return new UNL_MediaYak_FeedList(array('filter'=>new UNL_MediaYak_FeedList_Filter_BySubscription($this)));
    }

    /**
     * Get the media filter specified by this subscription
     * 
     * @return UNL_MediaYak_Filter
     */
    protected function getFilter()
    {
        $class = $this->filter_class;
        return new $class($this->filter_option);
    }

    /**
     * Get the list of media matching this subscription
     * 
     * @return UNL_MediaYak_MediaList
     */
    protected function getMediaList()
    {
        return new UNL_MediaYak_MediaList(array('filter'=>$this->getFilter()));
    }
}