<?php

abstract class UNL_MediaYak_Models_BaseFeedHasNSElement extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('feed_has_nselement');
    $this->hasColumn('feed_id',   'integer',    4, array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('nselement', 'string',  null, array('primary' => true, 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('value',     'string',  null, array('primary' => false, 'notnull' => true, 'autoincrement' => false, 'minlength'=>1));

  }

  public function setUp()
  {
      $this->hasOne('UNL_MediaYak_Feed',  array('local'   => 'feed_id',
                                                'foreign' => 'id'));
      parent::setUp();
  }
  
    function preInsert($event)
    {
        if (!preg_match('/^'.$this->getXMLNS().':(.*)/', $this->nselement)) {
            $event->skipOperation();
            return;
        }
        if (empty($this->value)) {
            $event->skipOperation();
            return;
        }
    }
    
    function preUpdate($event)
    {
        if (!preg_match('/^'.$this->getXMLNS().':(.*)/', $this->nselement)) {
            $event->skipOperation();
            return;
        }
        if (empty($this->value)) {
            $this->delete();
            $event->skipOperation();
            return;
        }
    }
    
    function preDelete($event)
    {
        if (!preg_match('/^'.$this->getXMLNS().':(.*)/', $this->nselement)) {
            $event->skipOperation();
            return;
        }
    }
    
    function getXMLNS()
    {
        return $this->xmlns;
    }

}
