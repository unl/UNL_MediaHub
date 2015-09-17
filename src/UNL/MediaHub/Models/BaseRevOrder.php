<?php
abstract class UNL_MediaHub_Models_BaseRevOrder extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('rev_orders');
        $this->hasColumn('id',               'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',         'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => false));
        $this->hasColumn('datecreated',      'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('dateupdated',      'timestamp', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('costobjectnumber', 'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('uid',              'string',    null, array('primary' => false,  'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('rev_order_number', 'string',    null, array('primary' => false,  'notnull' => false,'autoincrement' => false));
        $this->hasColumn('status',           'string',    null, array('primary' => false,  'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('media_duration',   'string',    null, array('primary' => false,  'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('estimate',         'string',    null, array('primary' => false,  'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('error_text',       'string',    null, array('primary' => false,  'notnull' => false, 'autoincrement' => false));
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
