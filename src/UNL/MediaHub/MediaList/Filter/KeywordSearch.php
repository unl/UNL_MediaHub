<?php 
class UNL_MediaHub_MediaList_Filter_KeywordSearch implements UNL_MediaHub_NativeSqlFilter
{
    protected $query;
    
    function __construct($query)
    {
        $this->query = $query;
    }
    
    function apply(Doctrine_RawSql &$query)
    {
        $query->addFrom('LEFT JOIN media_has_nselement mns ON (mns.media_id = m.id)');
        $query->where('mns.element = "keywords" AND mns.value LIKE ?', array('%'.$this->query.'%'));
    }
    
    function getLabel()
    {
        return 'Search results for keyword &lsquo;'.htmlentities($this->query, ENT_QUOTES).'&rsquo;';
    }
    
    function getType()
    {
        return 'tags';
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
        return 'Find media that has a specific tag';
    }
}
