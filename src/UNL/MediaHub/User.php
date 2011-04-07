<?php
class UNL_MediaHub_User extends UNL_MediaHub_Models_BaseUser
{
    /**
     * Get a user by uid/username
     *
     * @param string $uid
     *
     * @return UNL_MediaHub_User
     */
    public static function getByUid($uid)
    {
        $user = Doctrine::getTable('UNL_MediaHub_User')->find($uid);
        if ($user) {
            return $user;
        }
        return self::addUser($uid);
    }
    
    /**
     * Add a new user.
     *
     * @param string $uid User id
     *
     * @return UNL_MediaHub_User
     */
    public static function addUser($uid)
    {
        $user = new self();
        $data = array('uid'         => $uid,
                      'datecreated' => date('Y-m-d H:i:s'));
        $user->fromArray($data);
        $user->save();
        // Create the defaul feed for the user
        UNL_MediaHub_Feed::addFeed(array('title'       =>'Your Channel ('.$user->uid.')',
                                         'description' =>'This is your default channel'),
                                   $user);
        return $user;
    }
    
    /**
     * return a list of feeds for this user.
     *
     * @return UNL_MediaHub_FeedList
     */
    function getFeeds($options = array())
    {
        $options['filter'] = new UNL_MediaHub_FeedList_Filter_ByUser($this);
        return new UNL_MediaHub_User_FeedList($options);
    }
}
