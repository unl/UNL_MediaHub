<?php
class UNL_MediaHub_Subscription extends UNL_MediaHub_Models_BaseSubscription
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

        foreach ($media_list->items as $media) {
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
        return new UNL_MediaHub_FeedList(array('filter'=>new UNL_MediaHub_FeedList_Filter_BySubscription($this)));
    }

    /**
     * Get the media filter specified by this subscription
     * 
     * @return UNL_MediaHub_Filter
     */
    protected function getFilter()
    {
        $class = $this->filter_class;
        return new $class($this->filter_option);
    }

    /**
     * Get the list of media matching this subscription
     * 
     * @return UNL_MediaHub_MediaList
     */
    protected function getMediaList()
    {
        return new UNL_MediaHub_MediaList(array('filter'=>$this->getFilter()));
    }

    function getResultURL()
    {
        return $this->getMediaList()->getURL();
    }
}