<?php
class UNL_MediaHub_SubscriptionList extends UNL_MediaHub_List
{
    public $options = array('orderby' => 'datecreated',
                            'order'   => 'DESC',
                            'page'    => 0,
                            'limit'   => -1);
   
    public $tables = 'UNL_MediaHub_Subscription s';

    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('s.'.$this->options['orderby'].' '.$this->options['order']);
    }
}