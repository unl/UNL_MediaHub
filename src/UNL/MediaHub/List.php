<?php
/**
 * An abstract class for a list of items
 * 
 * @author bbieber
 */
abstract class UNL_MediaHub_List implements Countable, UNL_MediaHub_CacheableInterface
{
    public $options = array('page'=>0, 'limit'=>10);
    
    /**
     * total number of items
     */
    public $total = 0;
    
    public $first;
    
    public $last;
    
    /**
     * The pager object
     * 
     * @var Doctrine_Pager
     */
    public $pager;
    
    public $label;
    
    static public $url;
    
    /**
     * The tables used in this list.
     * 
     * @var string
     */
    public $tables = 'null';
    
    /**
     * Construct a list of items.
     * 
     * @param $filter Filter to use when filtering.
     * 
     * @return UNL_MediaHub_List
     */
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->filterInputOptions();
    }
    
    function getCacheKey()
    {
        return serialize($this->options);
    }
    
    function preRun($cached)
    {
        
    }
    
    function run()
    {
        $query = new Doctrine_Query();
        $query->from($this->tables);
        
        $this->setOrderBy($query);
        if (!empty($this->options['filter'])) {
            $this->options['filter']->apply($query);
            $this->label = $this->options['filter']->getLabel();
        }

        $pager = new Doctrine_Pager($query, $this->options['page'], $this->options['limit']);
        $pager->setCountQuery($query);

        $this->items = $pager->execute();
        $this->total = $pager->getNumResults();
        $this->first = $pager->getFirstIndice();
        $this->last  = $pager->getLastIndice();

        $this->pager = $pager;
        
        $this->getURL();
    }
    
    abstract function setOrderBy(Doctrine_Query &$query);
    
    /**
     * Function to allow filtering input options
     * 
     * @return unknown_type
     */
    function filterInputOptions()
    {
        
    }
    
    /**
     * Return the number of total items in the list.
     * 
     * @return int
     */
    function count()
    {
        return $this->total;
    }
    
    /**
     * Returns a url to describe this specific list.
     * 
     * @return string
     */
    function getURL()
    {
        $this->url = self::$url;
        
        $this->url .= '&amp;orderby=' . $this->options['orderby'];
        
        $this->url .= '&amp;order=' . $this->options['order'];
        
        return $this->url;
    }
}
?>