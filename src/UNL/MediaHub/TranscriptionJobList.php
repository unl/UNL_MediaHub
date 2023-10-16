<?php
class UNL_MediaHub_TranscriptionJobList extends UNL_MediaHub_List
{
    public $options = array(
        'orderby' => 'datecreated',
        'order'   => 'DESC',
        'page'    => 0,
        'limit'   => 500,
        'additional_filters' => array(),
    );

    public $tables = 'UNL_MediaHub_TranscriptionJob jobs';

    public $items = array();

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->filterInputOptions();
        $this->setUpFilter();
        $this->run();
    }

    public function setUpFilter()
    {
        if (isset($this->options['media_id'])
            && !empty($this->options['media_id'])) {
            $this->options['filter'] = new UNL_MediaHub_TranscriptionJobList_Filter_ByMedia($this->options['media_id']);
        }

        if (isset($this->options['all_not_complete'])) {
            $this->options['filter'] = new UNL_MediaHub_TranscriptionJobList_Filter_NotComplete();
        }
    }

    public function setOrderBy(Doctrine_Query_Abstract $query)
    {
        $query->orderby('jobs.'.$this->options['orderby'].' '.$this->options['order']);
    }

    /**
     * @return Doctrine_Query_Abstract
     */
    protected function createQuery()
    {
        return new Doctrine_Query();
    }
}
