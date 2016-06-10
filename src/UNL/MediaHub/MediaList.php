<?php

class UNL_MediaHub_MediaList extends UNL_MediaHub_List
{
    
    public $options = array(
        'orderby'            => 'datecreated',
        'order'              => 'DESC',
        'page'               => 0,
        'limit'              => 12,
        'filter'             => null,
        'additional_filters' => array(),
        'f'                  => '',
    );
   
    public $tables = 'media m';
    protected $select = '{m.id}';

    public function __construct($options = array())
    {
        //Dont paginate if we are not viewing html.
        if (isset($options['format']) && $options['format'] !== 'html') {
            $options['limit'] = 0;
        }
        
        $this->options = $options + $this->options;
        $this->filterInputOptions();
        $this->setUpFilter();
        $this->run();
    }

    public function setUpFilter()
    {

        if (isset($this->options['q'])
            && !empty($this->options['q'])) {
            $this->options['filter'] = new UNL_MediaHub_MediaList_Filter_TextSearch($this->options['q']);
        }
        if (isset($this->options['t'])
            && !empty($this->options['t'])) {
            $this->options['filter'] = new UNL_MediaHub_MediaList_Filter_KeywordSearch($this->options['t']);
        }

        // Default filter
        if (!isset($this->options['filter'])) {
            $this->options['filter'] = new UNL_MediaHub_MediaList_Filter_ShowRecent();
        }

        $this->options['additional_filters'][] = new UNL_MediaHub_MediaList_Filter_Privacy(UNL_MediaHub_AuthService::getInstance()->getUser());

        if (isset($this->options['f'])) {
            switch ($this->options['f']) {
                case 'video':
                    $this->options['additional_filters'][] = new UNL_MediaHub_MediaList_Filter_Video();
                    break;
                case 'audio':
                    $this->options['additional_filters'][] = new UNL_MediaHub_MediaList_Filter_Audio();
                    break;
            }
        }
    }
    
    public function filterInputOptions()
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
            case 'play_count':
            case 'popular_play_count':
            case 'title_a_z':
            case 'title_z_a':
                break;
            default:
                $this->options['orderby'] = 'datecreated';
                break;
        }
        
        $this->options['additional_filters'] = array();
        $this->options['page'] = (int)$this->options['page'];
    }
    
    public function setOrderBy(Doctrine_Query_Abstract $query)
    {
        $order_by = $this->options['orderby'];
        if (in_array($order_by, array('title_a_z', 'title_z_a'))) {
            $order_by = 'title';
        }
        
        $query->orderby('m.'.$order_by.' '.$this->options['order']);
    }
    
    public function getURL($params = array())
    {
        if (!empty($this->options['filter'])) {
            switch ($this->options['filter']->getType()) {
                case 'feed':
                    $url = UNL_MediaHub_Controller::getURL($this->options['filter']->getValue());
                    break;
                default:
                    $url = UNL_MediaHub_Controller::getURL();
                    $url .= 'search/';
                    $params['q'] = urlencode($this->options['filter']->getValue());
                    break;
            }
        }
        
        if (!isset($params['orderby'])) {
            $params['orderby'] = $this->options['orderby'];
        }

        if (!isset($params['order'])) {
            $params['order'] = $this->options['order'];
        }
        
        if (!isset($params['f'])) {
            $params['f'] = $this->options['f'];
        }
        
        $url = UNL_MediaHub_Controller::addURLParams($url, $params);
        
        return $url;
    }

    /**
     * Get related feeds for the current list
     *
     * @param array $options
     * @return UNL_MediaHub_User_FeedList
     */
    public function getRelatedFeeds($options = array())
    {
        if ($this->options['filter']->getType() != 'search') {
            return false;
        }
        
        $options['filter'] = new UNL_MediaHub_FeedList_Filter_WithTerm($this->options['filter']->getValue());
        $feeds = new UNL_MediaHub_FeedList($options);
        $feeds->run();
        return $feeds;
    }

    /**
     * @return Doctrine_Query_Abstract
     */
    protected function createQuery()
    {
        $query = new Doctrine_RawSql();
        $query->addComponent('m', 'UNL_MediaHub_Media m');
        
        return $query;
    }
}
