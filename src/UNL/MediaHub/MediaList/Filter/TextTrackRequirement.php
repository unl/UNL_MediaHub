<?php
class UNL_MediaHub_MediaList_Filter_TextTrackRequirement implements UNL_MediaHub_Filter
{
    
    function apply(Doctrine_Query &$query)
    {
        //All videos since the rev.com integration require captioning.
        $where = '(m.media_text_tracks_id IS NOT NULL OR m.datecreated < "' . UNL_MediaHub_Controller::$caption_requirement_date . '")';
        
        $query->andWhere($where);
    }

    function getLabel()
    {
        return 'Caption Requirement';
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
        return 'Show only media that meets the caption requirement';
    }
}
