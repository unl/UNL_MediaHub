<?php
abstract class UNL_MediaHub_Models_BaseMediaTrack extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('rev_orders');
        $this->hasColumn('id',               'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',         'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated',      'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('costobjectnumber', 'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('uid',              'string',    null, array('primary' => true,  'notnull' => true, 'autoincrement' => false));
    }

    public function setUp()
    {
        $this->hasOne('UNL_MediaHub_Media',
            array(
                'local'   => 'media_id',
                'foreign' => 'id'
            )
        );
        $this->hasOne('UNL_MediaHub_User',
            array(
                'local'   => 'uid',
                'foreign' => 'uid'
            )
        );
        parent::setUp();
    }
}
