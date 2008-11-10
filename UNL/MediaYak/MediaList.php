<?php

class UNL_MediaYak_MediaList
{
    public $options = array();
    
    /**
     * Collection of media
     */
    public $media;
    
    /**
     * total number of releases
     */
    public $total = 0;
    
    public $first;
    
    public $last;
    
    public $pager;
    
    static public $results_per_page = 10;
    
    public $label;
    
    public $url;
    
    public function __construct(UNL_MediaYak_MediaList_Filter $filter = null)
    {
        
        $this->options = $_GET + array('orderby' => 'datecreated',
                                       'order'   => 'DESC',
                                       'page'    => 0);
        $this->filterInputOptions();
        
        $query = new Doctrine_Query();
        $query->from('UNL_MediaYak_Media m');
        
        $query->orderby('m.'.$this->options['orderby'].' '.$this->options['order']);

        if ($filter) {
            $this->options['filter'] = $filter;
            $filter->apply($query);
            $this->label = $filter->getLabel();
        }

        $pager = new Doctrine_Pager($query, $this->options['page'], self::$results_per_page);
        $pager->setCountQuery($query);
        
        $this->media = $pager->execute();
        $this->total = $pager->getNumResults();
        $this->first = $pager->getFirstIndice();
        $this->last  = $pager->getLastIndice();

        $this->pager = $pager;
        
        $this->getURL();
    }
    
    function filterInputOptions()
    {
        switch ($this->options['order']) {
            case 'ASC':
            case 'DESC':
                break;
            default:
                $this->options['order'] = 'DESC';
                break;
        }
        
        switch ($this->options['orderby']) {
            case 'datecreated':
            case 'eventdate':
            case 'headline':
                break;
            default:
                $this->options['orderby'] = 'datecreated';
                break;
        }
        
        $this->options['page'] = (int)$this->options['page'];
    }
    
    function getURL()
    {
        $this->url = UNL_MediaYak_Controller::getURL();
        if (isset($this->options['filter'])) {
            switch ($this->options['filter']->getType()) {
                case 'tag':
                case 'year':
                    $this->url .= '&amp;filter='
                               . $this->options['filter']->getType()
                               . ':'
                               . $this->options['filter']->getValue();
                    break;
                case 'search':
                    $this->url .= '&amp;view=search&amp;q='
                               . urlencode($this->options['filter']->getValue());
                    break;
            }
        }
        
        $this->url .= '&amp;orderby=' . $this->options['orderby'];
        
        $this->url .= '&amp;order=' . $this->options['order'];
        
        return $this->url;
    }
}

?>