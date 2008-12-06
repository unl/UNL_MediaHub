<?php
abstract class UNL_MediaYak_Models_BaseUser extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('user');
    $this->hasColumn('uid',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
  }
  
}