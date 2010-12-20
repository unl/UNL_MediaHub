<?php
/**
 * A feed within the mediayak system.
 * 
 * @author bbieber
 */
class UNL_MediaYak_Feed extends UNL_MediaYak_Models_BaseFeed
{
    protected $namespaces = array();
    
    /**
     * Get by ID
     *
     * @param int $id The id of the feed to get
     *
     * @return UNL_MediaYak_Feed
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
    
    /**
     * get a feed by the title
     *
     * @param string $title Title of the feed/channel
     *
     * @return UNL_MediaYak_Feed
     */
    static function getByTitle($title)
    {
        return Doctrine::getTable(__CLASS__)->findOneByTitle($title);
    }
    
    /**
     * Add Media to the feed
     *
     * @param UNL_MediaYak_Media          $media    The media to add
     * @param UNL_MediaYak_Media_MetaData $metadata unused
     *
     * @return unknown
     */
    function addMedia(UNL_MediaYak_Media $media, UNL_MediaYak_Media_MetaData $metadata = null)
    {
        $this->UNL_MediaYak_Media[] = $media;
        return $this->save();
    }

    function removeMedia(UNL_MediaYak_Media $media)
    {
        $q = Doctrine_Query::create()
            ->delete('UNL_MediaYak_Feed_Media')
            ->addWhere('feed_id = ?', $this->id)
            ->addWhere('media_id = ?', $media->id);

        return $q->execute();
    }

    /**
     * Add a feed to the system
     *
     * @param array             $data Associative array of details.
     * @param UNL_MediaYak_User $user User creating this feed
     *
     * @return UNL_MediaYak_Feed
     */
    public static function addFeed($data, UNL_MediaYak_User $user)
    {
        $data = array_merge($data, array('datecreated' => date('Y-m-d H:i:s'),
                                         'uidcreated'  => $user->uid));
        $feed = new self();
        $feed->fromArray($data);
        $feed->save();
        $feed->addUser($user);
        return $feed;
    }
    
    /**
     * Add a user with all permissions to this feed.
     *
     * @param UNL_MedaiYak_User $user The user to grant permission to
     * 
     * @return void
     */
    function addUser(UNL_MediaYak_User $user)
    {
        $permissions = new ReflectionClass('UNL_MediaYak_Permission');
        foreach($permissions->getConstants() as $key=>$permission) {
            if (substr($key, 0, 9) == 'USER_CAN_') {
                $this->grantUserPermission($user, UNL_MediaYak_Permission::getByID($permission));
            }
        }
    }

    function removeUser(UNL_MediaYak_User $user)
    {
        $q = Doctrine_Query::create()
            ->delete('UNL_MediaYak_User_Permission')
            ->addWhere('feed_id = ?', $this->id)
            ->addWhere('user_uid = ?', $user->uid);

        return $q->execute();
    }
    
    /**
     * Check if the user has a given permission for this feed.
     *
     * @param UNL_MediaYak_User       $user       User to check
     * @param UNL_MediaYak_Permission $permission Permission
     *
     * @return bool
     */
    function userHasPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission)
    {
        return UNL_MediaYak_Permission::userHasPermission($user, $permission, $this);
    }
    
    /**
     * Grant a user permission over the feed.
     *
     * @param UNL_MediaYak_User       $user       User to grant permission for
     * @param UNL_MediaYak_Permission $permission Permission to grant
     *
     * @return bool
     */
    function grantUserPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission)
    {
        return UNL_MediaYak_Permission::grantUserPermission($user, $permission, $this);
    }
    
    function addNamespace(UNL_MediaYak_Feed_NamespacedAttributes $namespace)
    {
        
    }
    
    /**
     * Check if this feed is linked to the related media.
     *
     * @param UNL_MediaYak_Media $media The media file we're checking
     *
     * @return bool
     */
    public function hasMedia(UNL_MediaYak_Media $media)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaYak_Feed_Media')
              ->where('feed_id = ? AND media_id = ?', array($this->id, $media->id));
        return $query->count();
    }

    /**
     * Check if the feed has an image set
     * 
     * @return bool
     */
    public function hasImage()
    {
        if (!empty($this->image_data)) {
            return true;
        }
        return false;
    }

    public function getEditorEmail()
    {
        if ($user = @file_get_contents('http://peoplefinder.unl.edu/service.php?uid='.urlencode($this->uidcreated).'&format=json')) {
            $user = json_decode($user);
            if (isset($user->mail)) {
                if (is_object($user->mail)) {
                    return current($user->mail);
                }
                return (string)$user->mail;
            }
        }
        return false;
    }
    
}
