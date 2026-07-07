<?php
class UNL_MediaHub_MediaList_Filter_NoLiveCaptions implements UNL_MediaHub_Filter
{
    protected $query;

    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('(m.media_text_tracks_id IS NULL and m.id not in (SELECT tj.media_id FROM mediahub.transcription_jobs tj WHERE tj.status = "ERROR" AND tj.uid = "AUTO CAPTION"))');
    }

    public function getLabel()
    {
        return 'NoLiveCaptions';
    }

    public function getType()
    {
        return '';
    }

    public function getValue()
    {
        return $this->query;
    }

    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'List of media with no captions';
    }
}
