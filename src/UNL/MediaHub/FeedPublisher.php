<?php
/**
 * Interface publishers must implement.
 * 
 * @author bbieber
 */
interface FeedPublisher
{
    /**
     * The publish action which will be called for publishers.
     * 
     * @param UNL_MediaYak_Feed $feed The feed to be published.
     * 
     * @return bool
     */
    function publish(UNL_MediaYak_Feed $feed);
}
?>