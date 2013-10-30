<?php 
class UNL_MediaHub_MediaList_Filter_KeywordSearch implements UNL_MediaHub_Filter
{
    protected $query;
    
    function __construct($query)
    {
        $this->query = $query;
    }
    
    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_Feed_Media_NamespacedElements_itunes.media_id = m.id AND UNL_MediaHub_Feed_Media_NamespacedElements_itunes.element = "keywords" AND UNL_MediaHub_Feed_Media_NamespacedElements_itunes.value LIKE ? AND m.privacy = ?', array('%'.$this->query.'%', 'PUBLIC'));
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
