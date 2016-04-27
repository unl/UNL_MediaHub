<?php
class UNL_MediaHub_MediaTextTrackFileList extends UNL_MediaHub_List
{
    public $options = array(
        'orderby' => 'datecreated',
        'order'   => 'DESC',
        'page'    => 0,
        'limit'   => 0,
        'additional_filters' => array(),
    );

    public $tables = 'UNL_MediaHub_MediaTextTrackFile ttf';

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->filterInputOptions();
        $this->setUpFilter();
        $this->run();
    }

    public function setUpFilter()
    {
        if (isset($this->options['text_track_id'])
            && !empty($this->options['text_track_id'])) {
            $this->options['filter'] = new UNL_MediaHub_MediaTextTrackFileList_Filter_ByTextTrack($this->options['text_track_id']);
        }
    }

    function setOrderBy(Doctrine_Query_Abstract &$query)
    {
        $query->orderby('ttf.'.$this->options['orderby'].' '.$this->options['order']);
    }

    /**
     * @return Doctrine_Query_Abstract
     */
    protected function createQuery()
    {
        return new Doctrine_Query();
    }
}
