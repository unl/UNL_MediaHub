<?php

abstract class UNL_MediaHub_Models_BaseMediaHasNSElement extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media_has_nselement');
        $this->hasColumn('media_id',   'integer',    4, array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('xmlns',      'string',  null, array('primary' => true, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('element',    'string',  null, array('primary' => true, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('attributes', 'array',   null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('value',      'string',  null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        
        $this->setSubclasses(array(
                'UNL_MediaHub_Feed_Media_NamespacedElements_itunesu' => array('xmlns' => 'itunesu'),
                'UNL_MediaHub_Feed_Media_NamespacedElements_itunes'  => array('xmlns' => 'itunes'),
                'UNL_MediaHub_Feed_Media_NamespacedElements_media'   => array('xmlns' => 'media'),
                'UNL_MediaHub_Feed_Media_NamespacedElements_boxee'   => array('xmlns' => 'boxee'),
                'UNL_MediaHub_Feed_Media_NamespacedElements_geo'     => array('xmlns' => 'geo')
            ));
    }
    
    public function setUp()
    {
        $this->hasOne('UNL_MediaHub_Media',  array('local'   => 'media_id',
                                                   'foreign' => 'id'));
        parent::setUp();
    }
  
    function preInsert($event)
    {
        if (empty($this->value) && empty($this->attributes)) {
            $event->skipOperation();
            return;
        }
        if (!empty($this->attributes) && !is_array($this->attributes)) {
            if ($test = unserialize($this->attributes)) {
                $this->attributes = $test;
            }
        }
    }
    
    function preUpdate($event)
    {
        if (empty($this->value) && empty($this->attributes)) {
            $this->delete();
            $event->skipOperation();
            return;
        }
        if (!empty($this->attributes) && !is_array($this->attributes)) {
            if ($test = unserialize($this->attributes)) {
                $this->attributes = $test;
            }
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
