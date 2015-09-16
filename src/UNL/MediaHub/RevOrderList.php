<?php
class UNL_MediaHub_RevOrderList extends UNL_MediaHub_List
{
    public $options = array(
        'orderby' => 'datecreated',
        'order'   => 'DESC',
        'page'    => 0,
        'limit'   => 0,
        'additional_filters' => array(),
    );

    public $tables = 'UNL_MediaHub_RevOrder ro';

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
            $this->options['filter'] = new UNL_MediaHub_RevOrderList_Filter_ByMedia($this->options['media_id']);
        }

        if (isset($this->options['all_not_complete'])) {
            $this->options['filter'] = new UNL_MediaHub_RevOrderList_Filter_NotComplete();
        }

        if (isset($this->options['for_billing'])) {
            $after_date = null;
            if (isset($this->options['after_date'])) {
                $after_date = $this->options['after_date'];
            }
            
            $this->options['filter'] = new UNL_MediaHub_RevOrderList_Filter_ReadyForBilling($after_date);
        }
    }

    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('ro.'.$this->options['orderby'].' '.$this->options['order']);
    }
}