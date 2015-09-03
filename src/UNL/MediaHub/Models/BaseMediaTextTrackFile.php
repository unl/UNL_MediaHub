<?php
abstract class UNL_MediaHub_Models_BaseMediaTextTrackFile extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_text_tracks');
        $this->hasColumn('id',              'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_text_tracks_id', 'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated',     'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('kind',            'string', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('format',          'string', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('language',        'string', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('file_contents',   'string', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasOne();
    }

    public function setUp()
    {
        $this->hasOne('UNL_MediaHub_MediaTextTrack',
            array(
                'local'   => 'media_text_tracks_id',
                'foreign' => 'id'
            )
        );
        parent::setUp();
    }
}
