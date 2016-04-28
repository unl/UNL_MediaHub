<?php 
class UNL_MediaHub_MediaList_Filter_KeywordSearch implements UNL_MediaHub_Filter
{
    protected $query;
    
    public function __construct($query)
    {
        $this->query = $query;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->addFrom('LEFT JOIN media_has_nselement mns ON (mns.media_id = m.id)');
        $query->where('mns.element = "keywords" AND mns.value LIKE ?', array('%'.$this->query.'%'));
    }
    
    public function getLabel()
    {
        return 'Search results for keyword &lsquo;'.htmlentities($this->query, ENT_QUOTES).'&rsquo;';
    }
    
    public function getType()
    {
        return 'tags';
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
        return 'Find media that has a specific tag';
    }
}
