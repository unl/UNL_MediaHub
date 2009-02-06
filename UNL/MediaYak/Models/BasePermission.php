<?php
abstract class UNL_MediaYak_Models_BasePermission extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('feeds');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('title',         'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false, 'unique' => true));
        $this->hasColumn('description',   'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
    }
  
}