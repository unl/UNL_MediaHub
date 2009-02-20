<?php

abstract class UNL_MediaYak_Feed_Media_NamespacedElements extends UNL_MediaYak_Models_BaseMediaHasNSElement
{
    public function getXMLNS()
    {
        return $this->xmlns;
    }
    
    public function getURI()
    {
        return $this->uri;
    }
    
    abstract function getItemElements();
    
    public static function mediaHasElement($media_id, $element, $xmlns)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaYak_Feed_Media_NamespacedElements_'.$xmlns);
        $query->where('xmlns = ? AND media_id = ? AND element = ?', array($xmlns, $media_id, $element));
        return $query->fetchOne();
    }
}