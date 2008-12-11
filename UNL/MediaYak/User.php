<?php
class UNL_MediaYak_User extends UNL_MediaYak_Models_BaseUser
{   
    public static function getByUid($uid)
    {
        return Doctrine::getTable('UNL_MediaYak_User')->find($uid);
    }
    
    function getFeeds()
    {
        $filter = new UNL_MediaYak_FeedList_Filter_ByUser($this);
        return new UNL_MediaYak_FeedList($filter);
    }
}
