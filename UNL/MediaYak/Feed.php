<?php
class UNL_MediaYak_Feed extends UNL_MediaYak_Models_BaseFeed
{
    /**
     * Get a feed by PK.
     *
     * @param int $id ID of the feed.
     * @return UNL_MediaYak_Feed
     */
    static function getById($id)
    {
        return Doctrine::getTable('UNL_MediaYak_Feed')->find($id);
    }
    
    function addMedia(UNL_MediaYak_Media $media, UNL_MediaYak_Media_MetaData $metadata)
    {
    }
    
    function addUser(UNL_MediaYak_User $user)
    {
        $permissions = new ReflectionClass('UNL_MediaYak_Permission');
        foreach($permissions->getConstants() as $permission) {
            $this->grantUserPermission($user, UNL_MediaYak_Permission::getByID($permission));
        }
    }
    
    function grantUserPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission)
    {
        $data = array('user_uid'      => $user->uid,
                      'permission_id' => $permission->id,
                      'feed_id'       => $this->id);
        $user_permission = new UNL_MediaYak_User_Permission();
        $user_permission->fromArray($data);
        return $user_permission->save();
    }
}
?>