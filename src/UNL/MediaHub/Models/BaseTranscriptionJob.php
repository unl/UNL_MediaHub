<?php
abstract class UNL_MediaHub_Models_BaseTranscriptionJob extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('transcription_jobs');
        $this->hasColumn('id',          'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',    'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated', 'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('dateupdated', 'timestamp', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('uid',         'string',    null, array('primary' => false,  'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('job_id',      'string',    null, array('primary' => false,  'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('status',      'enum',      null, array('primary' => false,  'notnull' => true, 'autoincrement' => false, 'values' => array('SUBMITTED', 'WORKING', 'ERROR', 'FINISHED'), 'default' => 'SUBMITTED'));
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
