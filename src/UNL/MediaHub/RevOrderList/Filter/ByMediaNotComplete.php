<?php
class UNL_MediaHub_RevOrderList_Filter_ByMediaNotComplete implements UNL_MediaHub_Filter
{
    protected $media_id;

    public function __construct($media_id)
    {
        $this->media_id = $media_id;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('(ro.status NOT IN ("'.UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE.'", "'.UNL_MediaHub_RevOrder::STATUS_ERROR.'", "'.UNL_MediaHub_RevOrder::STATUS_CANCELLED.'") AND ro.media_id = ?)', $this->media_id);
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
