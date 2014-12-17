<?php
abstract class UNL_MediaHub_Models_BaseMediaViews extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_views');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id',      'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('ip_address',    'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
    }

}