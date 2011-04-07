<?php
abstract class UNL_MediaYak_Models_BaseUser extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('users');
        $this->hasColumn('uid',           'string',    null, array('primary' => true,  'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }
    
    public function setUp()
    {
        $this->hasMany('UNL_MediaYak_Permission',    array('local'    => 'uid',
                                                           'foreign'  => 'user_uid',
                                                           'refClass' => 'UNL_MediaYak_User_Permission'));
        parent::setUp();
    }

}