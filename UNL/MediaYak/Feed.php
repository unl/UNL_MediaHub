<?php
class UNL_MediaYak_Feed extends UNL_MediaYak_Models_BaseFeed
{
    /**
     * Get a feed by PK.
     *
     * @param int $id ID of the feed.
     *
     * @return UNL_MediaYak_Feed
     */
    static function getById($id)
    {
        return Doctrine::getTable('UNL_MediaYak_Feed')->find($id);
    }
    
    function addMedia(UNL_MediaYak_Media $media, UNL_MediaYak_Media_MetaData $metadata = null)
    {
        $this->feed->UNL_MediaYak_Media[] = $media;
        return $this->feed->save();
    }
    
    /**
     * Add all permissions for a user to this feed.
     *
     * @param UNL_MediaYak_User $user
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
    
    /**
     * Grant permission for a user.
     *
     * @param UNL_MediaYak_User $user
     * @param UNL_MediaYak_Permission $permission
     *
     * @return bool
     */
    function grantUserPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission)
    {
        return UNL_MediaYak_Permission::grantUserPermission($user, $permission, $this);
    }
}
?>