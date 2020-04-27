<?php
class UNL_MediaHub_RevOrderList_Filter_ReadyForBilling implements UNL_MediaHub_Filter
{
    protected $after_date;

    public function __construct($after_date = null)
    {
        $this->query = $after_date;
    }

    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('(ro.status IN ("' . UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE . '"))');

        if (!empty($this->after_date)) {
            $query->andWhere('ro.dateupdated >= ?', array($this->after_date));
        }
    }

    public function getLabel()
    {
        return '';
    }

    public function getType()
    {
        return '';
    }

    public function getValue()
    {
        return '';
    }

    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return '';
    }
}
