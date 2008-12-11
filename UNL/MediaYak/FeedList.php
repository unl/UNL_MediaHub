<?php
class UNL_MediaYak_FeedList extends UNL_Mediayak_List
{
    public $tables = 'UNL_MediaYak_Feed f, UNL_MediaYak_User u';
    
    public $options = array('orderby'=>'title');
    
    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('f.'.$this->options['orderby'].' '.$this->options['order']);
    }
}