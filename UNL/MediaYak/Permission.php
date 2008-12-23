<?php

class UNL_MediaYak_Permission extends UNL_MediaYak_Models_BasePermission
{
    const USER_CAN_INSERT = 1;
    const USER_CAN_UPDATE = 2;
    const USER_CAN_DELETE = 3;
    
    const USER_CAN_UPLOAD = 10;
    
    const USER_CAN_ADD_USER = 20;
    
    public static function userHasPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission, UNL_MediaYak_Feed $feed)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaYak_User_Permission u')
              ->where('u.user_uid = ? AND u.permission_id = ? AND u.feed_id = ?',
                      array($user->uid, $permission->id, $feed->id));
        $results = $query->execute();
        
        return count($results);
    }
    
    public static function getByID($id)
    {
        $permission = Doctrine::getTable('UNL_MediaYak_Permission')->find($id);
        if ($permission) {
            return $permission;
        }
        $permission = new self();
        $data = array('id'=>$id);
        $permission->fromArray($data);
        $permission->save();
        return $permission;
    }
    
    public static function getByTitle($title)
    {
        return Doctrine::getTable('UNL_MediaYak_Permission')->findByTitle($title);
    }
    
    public static function grantUserPermission(UNL_MediaYak_User $user, UNL_MediaYak_Permission $permission, UNL_MediaYak_Feed $feed)
    {
        $data = array('user_uid'      => $user->uid,
                      'permission_id' => $permission->id,
                      'feed_id'       => $feed->id);
        $user_permission = new UNL_MediaYak_User_Permission();
        $user_permission->fromArray($data);
        return $user_permission->save();
    }
}