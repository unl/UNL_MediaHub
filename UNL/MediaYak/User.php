<?php
class UNL_MediaYak_User extends UNL_MediaYak_Models_BaseUser
{   
    public static function getByUid($uid)
    {
        $user = Doctrine::getTable('UNL_MediaYak_User')->find($uid);
        if ($user) {
            return $user;
        }
        $user = new self();
        $data = array('uid'=>$uid,
                      'datecreated'=>date('Y-m-d H:i:s'));
        $user->fromArray($data);
        $user->save();
        return $user;
    }
    
    function getFeeds()
    {
        $filter = new UNL_MediaYak_FeedList_Filter_ByUser($this);
        return new UNL_MediaYak_FeedList($filter);
    }
}
