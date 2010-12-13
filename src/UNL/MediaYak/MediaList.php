<?php

class UNL_MediaYak_MediaList extends UNL_MediaYak_List
{
    
    public $options = array('orderby' => 'datecreated',
                            'order'   => 'DESC',
                            'page'    => 0,
                            'limit'   => 10);
   
    public $tables = 'UNL_MediaYak_Media m';

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->filterInputOptions();
        $this->setUpFilter();
    }

    function setUpFilter()
    {
        if (isset($this->options['q'])
            && !empty($this->options['q'])) {
            $this->options['filter'] = new UNL_MediaYak_MediaList_Filter_TextSearch($this->options['q']);
        }
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
    
    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('m.'.$this->options['orderby'].' '.$this->options['order']);
    }
    
    function getURL()
    {
        $params = array();

        $this->url = UNL_MediaYak_Controller::getURL();
        if (!empty($this->options['filter'])) {
            switch ($this->options['filter']->getType()) {
                case 'tag':
                case 'year':
                    $params['filter'] = $this->options['filter']->getType()
                                        . ':'
                                        . $this->options['filter']->getValue();
                    break;
                case 'search':
                    $this->url .= 'search/';
                    $params['q'] = urlencode($this->options['filter']->getValue());
                    break;
            }
        }
        
        $params['orderby'] = $this->options['orderby'];
        $params['order']   = $this->options['order'];

        $this->url = UNL_MediaYak_Controller::addURLParams($this->url, $params);
        
        return $this->url;
    }
}

?>