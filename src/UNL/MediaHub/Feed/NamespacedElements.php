<?php
abstract class UNL_MediaHub_Feed_NamespacedElements extends UNL_MediaHub_Models_BaseFeedHasNSElement
{
    abstract function getChannelElements();
    
    public static function feedHasElement($feed_id, $element, $xmlns)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaHub_Feed_NamespacedElements_'.$xmlns);
        $query->where('xmlns = ? AND feed_id = ? AND element = ?', array($xmlns, $feed_id, $element));
        return $query->fetchOne();
    }
}