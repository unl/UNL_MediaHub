<?php

class UNL_MediaHub_Permission extends UNL_MediaHub_Models_BasePermission
{
    const USER_CAN_INSERT = 1;
    const USER_CAN_UPDATE = 2;
    const USER_CAN_DELETE = 3;
    
    const USER_CAN_UPLOAD = 10;
    
    const USER_CAN_ADD_USER = 20;
    
    public static function userHasPermission(UNL_MediaHub_User $user, UNL_MediaHub_Permission $permission, UNL_MediaHub_Feed $feed)
    {
        if ($user->isAdmin()) {
            return true;
        }

        $query = new Doctrine_Query();
        $query->from('UNL_MediaHub_User_Permission u')
              ->where('u.user_uid = ? AND u.permission_id = ? AND u.feed_id = ?',
                      array($user->uid, $permission->id, $feed->id));
        $results = $query->execute();
        
        return count($results);
    }
    
    public static function getByID($id)
    {
        $permission = Doctrine::getTable('UNL_MediaHub_Permission')->find($id);
        if ($permission) {
            return $permission;
        }
        $permission = new self();
        $data = array('id'=>$id);
        switch ($id) {
            case self::USER_CAN_INSERT:
                $data['title'] = 'User Can Insert';
                break;
            case self::USER_CAN_UPDATE:
                $data['title'] = 'User Can Update';
                break;
            case self::USER_CAN_DELETE:
                $data['title'] = 'User Can Delete';
                break;
            case self::USER_CAN_UPLOAD:
                $data['title'] = 'User Can Upload';
                break;
            case self::USER_CAN_ADD_USER:
                $data['title'] = 'User Can Add User';
                break;
            default:
                return $permission;
        }
        
        $permission->fromArray($data);
        $permission->save();
        return $permission;
    }
    
    public static function getByTitle($title)
    {
        return Doctrine::getTable('UNL_MediaHub_Permission')->findByTitle($title);
    }
    
    public static function grantUserPermission(UNL_MediaHub_User $user, UNL_MediaHub_Permission $permission, UNL_MediaHub_Feed $feed)
    {
        $data = array('user_uid'      => $user->uid,
                      'permission_id' => $permission->id,
                      'feed_id'       => $feed->id);
        $user_permission = new UNL_MediaHub_User_Permission();
        $user_permission->fromArray($data);
        return $user_permission->save();
    }
}