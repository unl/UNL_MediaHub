<?php
class UNL_MediaHub_FeedList_Filter_WithTerm implements UNL_MediaHub_Filter
{
    protected $term = '';

    public function __construct($term)
    {
        $this->term = $term;
    }

    public function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('(f.title LIKE ? OR f.description LIKE ?) AND f.UNL_MediaHub_Media.id IS NOT NULL', array('%'.$this->term.'%', '%'.$this->term.'%'));
    }

    public function getLabel()
    {
        return 'Available Channels';
    }

    public function getType()
    {
        return '';
    }

    public function getValue()
    {
        return $this->term;
    }

    public function __toString()
    {
        return '';
    }
}