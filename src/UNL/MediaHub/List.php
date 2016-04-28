<?php
/**
 * An abstract class for a list of items
 * 
 * @author bbieber
 */
abstract class UNL_MediaHub_List implements Countable
{
    public $options = array(
        'page'               => 0,
        'limit'              => 10,
        'filter'             => null,
        'additional_filters' => array(),
    );
    
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
     * Select these fields
     * 
     * @var string
     */
    protected $select = null;
    
    public $ran = false;
    
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
        
        $this->run();
    }
    
    function run()
    {
        if ($this->ran) {
            //Don't rerun
            return false;
        }
        
        $query = $this->createQuery();
        $query->from($this->tables);
        $query->select($this->select);
        
        $this->setOrderBy($query);
        if (!empty($this->options['filter'])) {
            $this->options['filter']->apply($query);
            $this->label = $this->options['filter']->getLabel();
        }
        
        foreach ($this->options['additional_filters'] as $filter) {
            $filter->apply($query);
        }

        $pager = new Doctrine_Pager($query, $this->options['page'], $this->options['limit']);
        $pager->setCountQuery($query);

        $this->items = $pager->execute();
        $this->total = $pager->getNumResults();
        $this->first = $pager->getFirstIndice();
        $this->last  = $pager->getLastIndice();

        $this->pager = $pager;
        
        $this->ran = true;
    }
    
    abstract public function setOrderBy(Doctrine_Query_Abstract $query);

    /**
     * @return Doctrine_Query_Abstract
     */
    abstract protected function createQuery();
    
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
}
