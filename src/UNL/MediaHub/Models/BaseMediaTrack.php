<?php
abstract class UNL_MediaHub_Models_BaseMediaTrack extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_tracks');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',      'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }

    public function setUp()
    {
        $this->hasOne('UNL_MediaHub_Media',
            array(
                'local'   => 'media_id',
                'foreign' => 'id'
            )
        );
        parent::setUp();
    }
}
