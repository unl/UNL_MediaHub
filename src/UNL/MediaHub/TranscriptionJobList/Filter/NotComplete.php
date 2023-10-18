<?php
class UNL_MediaHub_TranscriptionJobList_Filter_NotComplete implements UNL_MediaHub_Filter
{
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where(
            '(jobs.status IN ("'
            . UNL_MediaHub_TranscriptionJob::STATUS_SUBMITTED
            . '", "'
            . UNL_MediaHub_TranscriptionJob::STATUS_WORKING
            . '") )'
        );
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
