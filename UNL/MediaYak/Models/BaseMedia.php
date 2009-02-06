<?php
abstract class UNL_MediaYak_Models_BaseMedia extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('url',           'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('title',         'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('description',   'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('author',        'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }
    
    public function setUp()
    {
        $this->hasMany('UNL_MediaYak_Media_Comment', array('local'    => 'id',
                                                           'foreign'  => 'media_id'));
        $this->hasMany('UNL_MediaYak_Feed',          array('local'    => 'media_id',
                                                           'foreign'  => 'feed_id',
                                                           'refClass' => 'UNL_MediaYak_Feed_Media'));
        parent::setUp();
    }

}