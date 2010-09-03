<?php

class UNL_MediaYak_MediaList extends UNL_MediaYak_List
{
    
    public $options = array('orderby' => 'datecreated',
                            'order'   => 'DESC',
                            'page'    => 0,
                            'limit'   => 10);
   
    public $tables = 'UNL_MediaYak_Media m';
    
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
        $this->url = UNL_MediaYak_Controller::getURL();
        if (!empty($this->options['filter'])) {
            switch ($this->options['filter']->getType()) {
                case 'tag':
                case 'year':
                    $this->url .= '&amp;filter='
                               . $this->options['filter']->getType()
                               . ':'
                               . $this->options['filter']->getValue();
                    break;
                case 'search':
                    $this->url .= 'search/?q='
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