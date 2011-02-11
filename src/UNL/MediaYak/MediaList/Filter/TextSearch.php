<?php
class UNL_MediaYak_MediaList_Filter_TextSearch implements UNL_MediaYak_Filter
{
    protected $query;
    
    function __construct($query)
    {
        $this->query = $query;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('m.title LIKE ? OR m.description LIKE ?', array('%'.$this->query.'%', '%'.$this->query.'%'));
    }
    
    function getLabel()
    {
        return 'Search results for &lsquo;'.htmlentities($this->query, ENT_QUOTES).'&rsquo;';
    }
    
    function getType()
    {
        return 'search';
    }
    
    function getValue()
    {
        return $this->query;
    }
    
    function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find media with matching title or description fields';
    }
}
