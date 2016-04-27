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
    function apply(Doctrine_Query_Abstract &$query);
    
    /**
     * A string label for this filter, mostly used in views
     * 
     * @return string
     */
    function getLabel();
    function getType();
    function getValue();
    
    /**
     * A string description for this filter.
     * 
     * @return string
     */
    function __toString();
}
