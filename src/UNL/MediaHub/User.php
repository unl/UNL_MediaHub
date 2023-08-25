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
        // Create the default feed for the user
        UNL_MediaHub_Feed::addFeed(array('title'       => $user->uid.'\'s channel',
                                         'description' =>'This is ' .$user->uid . '\'s default channel'),
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

    /**
     * Get an array of feed IDs
     * 
     * @return mixed
     * @throws Doctrine_Connection_Exception
     */
    public function getFeedIDs()
    {
        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $q = $db->prepare("SELECT DISTINCT f.id
            FROM feeds f
            INNER JOIN user_has_permission up ON up.user_uid = ? AND up.feed_id = f.id");

        $q->execute(array($this->uid));
        $result = $q->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }
    
    public function canTranscode()
    {
        return UNL_MediaHub::$auto_transcode_hls_all_users;
    }

    public function canTranscodePro()
    {
        if (UNL_MediaHub::$auto_transcode_pro_all_users) {
            return true;
        }

        if (in_array($this->uid, UNL_MediaHub::$auto_transcode_hls_users)) {
            return true;
        }

        return false;
    }

    public function isAdmin()
    {
        if (in_array($this->uid, UNL_MediaHub::$admins)) {
            return true;
        }

        return false;
    }
}
