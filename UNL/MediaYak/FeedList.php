<?php
class UNL_MediaYak_FeedList extends UNL_MediaYak_List
{
    public $tables = 'UNL_MediaYak_Feed f';
    
    public $options = array('orderby' => 'title',
                            'order'   => 'ASC');
    
    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('f.'.$this->options['orderby'].' '.$this->options['order']);
    }
}