<?php

abstract class UNL_MediaYak_Models_BaseFeedHasNSElement extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('feed_has_nslement');
    $this->hasColumn('feed_id',   'integer',    4, array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('nselement', 'string',  null, array('primary' => true, 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('value',     'string',  null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));

  }

  public function setUp()
  {
      $this->hasOne('UNL_MediaYak_Feed',  array('local'   => 'feed_id',
                                                'foreign' => 'id'));
      parent::setUp();
  }

}
