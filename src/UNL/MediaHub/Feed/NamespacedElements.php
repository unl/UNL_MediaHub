<?php
abstract class UNL_MediaYak_Feed_NamespacedElements extends UNL_MediaYak_Models_BaseFeedHasNSElement
{
    public function getXMLNS()
    {
        return $this->xmlns;
    }
    
    public function getURI()
    {
        return $this->uri;
    }
    
    abstract function getChannelElements();
    
    public static function feedHasElement($feed_id, $element, $xmlns)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaYak_Feed_NamespacedElements_'.$xmlns);
        $query->where('xmlns = ? AND feed_id = ? AND element = ?', array($xmlns, $feed_id, $element));
        return $query->fetchOne();
    }
}