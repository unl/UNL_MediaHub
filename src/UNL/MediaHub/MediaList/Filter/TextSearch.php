<?php
class UNL_MediaHub_MediaList_Filter_TextSearch implements UNL_MediaHub_Filter
{
    protected $query;
    
    public function __construct($query)
    {
        $this->query = $query;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('(m.title LIKE ? OR m.description LIKE ?)', array('%'.$this->query.'%', '%'.$this->query.'%'));
    }
    
    public function getLabel()
    {
        return 'Search results for &lsquo;'.htmlentities($this->query, ENT_QUOTES).'&rsquo;';
    }
    
    public function getType()
    {
        return 'search';
    }
    
    public function getValue()
    {
        return $this->query;
    }
    
    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find media with matching title or description fields';
    }
}
