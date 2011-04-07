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
        if (empty($this->options['filter'])) {
            $this->options['filter'] = new UNL_MediaHub_FeedList_Filter_WithRelatedMedia();
        }
    }
    /**
     * Customizes the ordering used in this list.
     * 
     * @see UNL/MediaHub/UNL_MediaHub_List#setOrderBy()
     */
    function setOrderBy(Doctrine_Query &$query)
    {
        $query->orderby('f.'.$this->options['orderby'].' '.$this->options['order']);
    }
}