<?php
abstract class UNL_MediaHub_Models_BaseFeed extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('feeds');
        $this->hasColumn('id',                'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('title',             'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('description',       'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('image_data',        'blob');
        $this->hasColumn('image_type',        'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('image_size',        'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('image_title',       'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('image_description', 'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('itunes_uri',        'string');
        $this->hasColumn('boxee',             'bool');
        $this->hasColumn('uidcreated',        'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('datecreated',       'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }

    public function setUp()
    {
        $this->hasMany('UNL_MediaHub_Media',    array('local'    => 'feed_id',
                                                      'foreign'  => 'media_id',
                                                      'refClass' => 'UNL_MediaHub_Feed_Media'));
        $this->hasMany('UNL_MediaHub_Feed_NamespacedElements_itunes',    array('local'    => 'id',
                                                                               'foreign'  => 'feed_id'));
        $this->hasMany('UNL_MediaHub_Feed_NamespacedElements_media',      array('local'    => 'id',
                                                                                'foreign'  => 'feed_id'));
        $this->hasMany('UNL_MediaHub_Feed_NamespacedElements_boxee',      array('local'    => 'id',
                                                                                'foreign'  => 'feed_id'));
        parent::setUp();
    }
  
}