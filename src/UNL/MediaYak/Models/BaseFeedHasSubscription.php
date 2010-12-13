<?php

abstract class UNL_MediaYak_Models_BaseFeedHasSubscription extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('feed_has_subscription');
        $this->hasColumn('feed_id',  'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('subscription_id', 'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    }
    
    public function setUp()
    {
        $this->hasOne('UNL_MediaYak_Feed',  array('local'   => 'feed_id',
                                                  'foreign' => 'id'));
        $this->hasOne('UNL_MediaYak_Subscription', array('local'   => 'subscription_id',
                                                         'foreign' => 'id'));
        parent::setUp();
    }

}