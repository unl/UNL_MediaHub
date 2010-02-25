<?php
class UNL_MediaYak_UserList extends UNL_MediaYak_List
{
    public $tables = 'UNL_MediaYak_User u';
    
    public $options = array('orderby'=>'uid');
    
    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('u.'.$this->options['orderby'].' '.$this->options['order']);
    }
}
