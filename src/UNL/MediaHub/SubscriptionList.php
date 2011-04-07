<?php
class UNL_MediaYak_SubscriptionList extends UNL_MediaYak_List
{
    public $options = array('orderby' => 'datecreated',
                            'order'   => 'DESC',
                            'page'    => 0,
                            'limit'   => -1);
   
    public $tables = 'UNL_MediaYak_Subscription s';

    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('s.'.$this->options['orderby'].' '.$this->options['order']);
    }
}