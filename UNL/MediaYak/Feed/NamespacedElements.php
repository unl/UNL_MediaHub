<?php
abstract class UNL_MediaYak_Feed_NamespacedElements
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
    abstract function getItemElements();
}