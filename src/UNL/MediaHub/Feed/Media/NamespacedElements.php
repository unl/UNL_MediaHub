<?php

abstract class UNL_MediaHub_Feed_Media_NamespacedElements extends UNL_MediaHub_Models_BaseMediaHasNSElement
{
    abstract function getItemElements();
    
    protected static function mediaHasElementNS($media_id, $element, $xmlns)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns);
        $query->where('xmlns = ? AND media_id = ? AND element = ?', array($xmlns, $media_id, $element));
        return $query->fetchOne();
    }
    
    protected static function mediaSetElementNS($media_id, $element, $xmlns, $value)
    {
        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_' . $xmlns;
        if (call_user_func($class . '::mediaHasElement', $media_id, $element)){
            $query = new Doctrine_Query();
            $query->update($class);
            $query->set('value', '?', $value);
            $query->where('xmlns = ? AND media_id = ? AND element = ?', array($xmlns, $media_id, $element));
            return $query->execute();
        } else {
            $keywords = new $class;
            $keywords->xmlns = $xmlns;
            $keywords->media_id = $media_id;
            $keywords->element = $element;
            $keywords->value = $value;
            return $keywords->save();
        }
        
    }
}