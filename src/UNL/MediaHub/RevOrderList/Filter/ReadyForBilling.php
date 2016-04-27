<?php
class UNL_MediaHub_RevOrderList_Filter_ReadyForBilling implements UNL_MediaHub_Filter
{
    protected $after_date;

    function __construct($after_date = null)
    {
        $this->query = $after_date;
    }

    function apply(Doctrine_Query_Abstract &$query)
    {
        $query->where('(ro.status IN ("' . UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE . '"))');

        if (!empty($this->after_date)) {
            $query->andWhere('ro.dateupdated >= ?', array($this->after_date));
        }
    }

    function getLabel()
    {
        return '';
    }

    function getType()
    {
        return '';
    }

    function getValue()
    {
        return '';
    }

    function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return '';
    }
}
