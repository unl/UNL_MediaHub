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
}