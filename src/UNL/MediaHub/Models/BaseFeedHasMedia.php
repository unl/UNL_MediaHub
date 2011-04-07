<?php

abstract class UNL_MediaHub_Models_BaseFeedHasMedia extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('feed_has_media');
        $this->hasColumn('feed_id',  'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('media_id', 'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    }
    
    public function setUp()
    {
        $this->hasOne('UNL_MediaHub_Feed',  array('local'   => 'feed_id',
                                                  'foreign' => 'id'));
        $this->hasOne('UNL_MediaHub_Media', array('local'   => 'media_id',
                                                  'foreign' => 'id'));
        parent::setUp();
    }

}
