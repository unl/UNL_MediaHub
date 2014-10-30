<?php
class UNL_MediaHub_FeedList_Filter_WithTerm implements UNL_MediaHub_Filter
{
    protected $term = '';

    public function __construct($term)
    {
        $this->term = $term;
    }

    public function apply(Doctrine_Query &$query)
    {
        $query->where('(f.title LIKE ? OR f.description LIKE ?)', array('%'.$this->term.'%', '%'.$this->term.'%'));
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