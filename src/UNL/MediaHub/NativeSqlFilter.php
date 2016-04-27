<?php
/**
 * A filter which can be applied to a UNL_MediaHub_List
 *
 * @author bbieber
 */
interface UNL_MediaHub_NativeSqlFilter
{
    /**
     * Applies the filter to the query
     *
     * @param Doctrine_Query $query The query to be filtered.
     *
     * @return void
     */
    function apply(Doctrine_RawSql &$query);

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
