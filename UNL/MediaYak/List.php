<?php
abstract class UNL_MediaYak_List implements Countable
{
    public $options = array('page'=>0);
    
    /**
     * total number of items
     */
    public $total = 0;
    
    public $first;
    
    public $last;
    
    public $pager;
    
    static public $results_per_page = 10;
    
    public $label;
    
    static public $url;
    
    public $tables = 'null';
    
    function __construct(UNL_MediaYak_Filter $filter = null)
    {
    
        $this->options = array_merge($this->options, $_GET);
        $this->filterInputOptions();
        
        $query = new Doctrine_Query();
        $query->from($this->tables);
        
        $this->setOrderBy($query);
        
        if ($filter) {
            $this->options['filter'] = $filter;
            $filter->apply($query);
            $this->label = $filter->getLabel();
        }

        $pager = new Doctrine_Pager($query, $this->options['page'], self::$results_per_page);
        $pager->setCountQuery($query);
        
        $this->items = $pager->execute();
        $this->total = $pager->getNumResults();
        $this->first = $pager->getFirstIndice();
        $this->last  = $pager->getLastIndice();

        $this->pager = $pager;
        
        $this->getURL();
    }
    
    abstract function setOrderBy(Doctrine_Query &$query);
    
    function filterInputOptions()
    {
        
    }
    
    function count()
    {
        return $this->total;
    }
    
    function getURL()
    {
        $this->url = self::$url;
        
        $this->url .= '&amp;orderby=' . $this->options['orderby'];
        
        $this->url .= '&amp;order=' . $this->options['order'];
        
        return $this->url;
    }
}
?>