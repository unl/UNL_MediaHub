<?php

abstract class UNL_MediaYak_Models_BaseMediaHasNSElement extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_has_nslement');
        $this->hasColumn('media_id',  'integer',    4, array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('nselement', 'string',  null, array('primary' => true, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('value',     'string',  null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }
    
    public function setUp()
    {
        $this->hasOne('UNL_MediaYak_Media',  array('local'   => 'media_id',
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
    
    /**
     * return the xmlnamespace shortname
     *
     * @return string
     */
    function getXMLNS()
    {
        return $this->xmlns;
    }

}
