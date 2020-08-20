<?php
abstract class UNL_MediaHub_Models_BaseMediaTextTrack extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_text_tracks');
        $this->hasColumn('id',               'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',         'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated',      'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('source',           'text', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('revision_comment', 'text', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('media_text_tracks_source_id','integer',4, array('unsigned' => 0, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
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
