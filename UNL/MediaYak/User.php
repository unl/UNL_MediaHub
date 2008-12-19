<?php
class UNL_MediaYak_User extends UNL_MediaYak_Models_BaseUser
{   
    /**
     * Get a user by uid/username
     *
     * @param string $uid
     * 
     * @return UNL_MediaYak_User
     */
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
    
    /**
     * return a list of feeds for this user.
     *
     * @return UNL_MediaYak_FeedList
     */
    function getFeeds()
    {
        $filter = new UNL_MediaYak_FeedList_Filter_ByUser($this);
        return new UNL_MediaYak_FeedList($filter);
    }
}
