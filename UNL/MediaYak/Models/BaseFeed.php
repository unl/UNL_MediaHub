<?php
abstract class UNL_MediaYak_Models_BaseFeed extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('feeds');
    $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('title',         'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('description',   'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('uidcreated',    'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
  }
  
  public function setUp()
  {
      $this->hasMany('UNL_MediaYak_Media',    array('local'    => 'feed_id',
                                                    'foreign'  => 'media_id',
                                                    'refClass' => 'UNL_MediaYak_Feed_Media'));
      $this->hasMany('UNL_MediaYak_Feed_NamespacedElements_itunes',    array('local'    => 'id',
                                                                             'foreign'  => 'feed_id'));
      parent::setUp();
  }
  
}