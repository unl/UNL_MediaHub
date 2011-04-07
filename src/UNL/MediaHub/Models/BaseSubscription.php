<?php
abstract class UNL_MediaHub_Models_BaseSubscription extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('subscriptions');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('filter_class',  'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('filter_option', 'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('uidcreated',    'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }

}