<?php
abstract class UNL_MediaHub_Models_BaseMedia extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('media');
        $this->hasColumn('id',            'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('url',           'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false,
                                                                   'regexp' => '/^(https?):\/\/([^\/])+unl\.edu\/.*/',
                                                                   'notblank' => true));
        $this->hasColumn('uidcreated',    'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('uidupdated',    'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('poster',        'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('length',        'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('type',          'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('title',         'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('description',   'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('author',        'string',    null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('privacy',       'enum',      null, array('primary' => false, 'notnull' => true, 'autoincrement' => false, 'values' => array('PUBLIC', 'UNLISTED', 'PRIVATE'), 'default' => 'PUBLIC'));
        $this->hasColumn('play_count',    'integer',   null, array('unsigned' => 0, 'primary' => false, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('datecreated',   'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('dateupdated',   'timestamp', null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
    }
    
    public function setUp()
    {
        $this->hasMany('UNL_MediaHub_Media_Comment', array('local'    => 'id',
                                                           'foreign'  => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed',          array('local'    => 'media_id',
                                                           'foreign'  => 'feed_id',
                                                           'refClass' => 'UNL_MediaHub_Feed_Media'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_itunesu',  array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_itunes',   array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_media',    array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_boxee',    array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_geo',      array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        $this->hasMany('UNL_MediaHub_Feed_Media_NamespacedElements_mediahub', array('local'   => 'id',
                                                                                    'foreign' => 'media_id'));
        parent::setUp();
    }

}