<?php
abstract class UNL_MediaYak_Models_BaseMediaComment extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('comments');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',      'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('uid',           'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('comment',       'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }
    
    public function setUp()
    {
        parent::setUp();
    }

}

