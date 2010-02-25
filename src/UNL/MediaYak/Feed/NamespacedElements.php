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
}