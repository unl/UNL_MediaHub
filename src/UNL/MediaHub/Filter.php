<?php
/**
 * A filter which can be applied to a UNL_MediaHub_List
 * 
 * @author bbieber
 */
interface UNL_MediaHub_Filter
{
    /**
     * Applies the filter to the query
     *
     * @param Doctrine_Query_Abstract $query The query to be filtered.
     *
     */
    public function apply(Doctrine_Query_Abstract $query);
    
    /**
     * A string label for this filter, mostly used in views
     * 
     * @return string
     */
    public function getLabel();
    public function getType();
    public function getValue();
    
    /**
     * A string description for this filter.
     * 
     * @return string
     */
    public function __toString();
}
