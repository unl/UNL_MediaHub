<?php
/**
 * A list of feeds
 * 
 * @author bbieber
 *
 */
class UNL_MediaHub_FeedList extends UNL_MediaHub_List
{
    /**
     * The list of tables in the database used by this list
     * 
     * @var string
     */
    public $tables = 'UNL_MediaHub_Feed f';
    
    /**
     * Array of options for this list
     * 
     * @var array
     */
    public $options = array('orderby' => 'title',
                            'order'   => 'ASC',
                            'page'    => 0,
                            'limit'   => 10);

    function __construct($options = array())
    {
        parent::__construct($options);
    }
    
    public function filterInputOptions()
    {
        if (empty($this->options['filter']) || !($this->options['filter'] instanceof UNL_MediaHub_Filter)) {
            $this->options['filter'] = new UNL_MediaHub_FeedList_Filter_WithRelatedMedia();
        }
        
        if (!in_array($this->options['order'], array('ASC', 'DESC'))) {
            $this->options['order'] = 'ASC';
        }
        
        if (!in_array($this->options['orderby'], array('title', 'datecreated', 'plays'))) {
            $this->options['orderby'] = 'title';
        }
    }
    /**
     * Customizes the ordering used in this list.
     * 
     * @see UNL/MediaHub/UNL_MediaHub_List#setOrderBy()
     */
    function setOrderBy(Doctrine_Query &$query)
    {
        if ($this->options['orderby'] == 'plays') {
            $query->orderBy('SUM(f.UNL_MediaHub_Media.play_count) '.$this->options['order']);
        } else {
            $query->orderby('f.'.$this->options['orderby'].' '.$this->options['order']);
        }
    }
}