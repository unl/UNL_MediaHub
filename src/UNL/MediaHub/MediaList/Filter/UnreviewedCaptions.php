<?php
class UNL_MediaHub_MediaList_Filter_UnreviewedCaptions implements UNL_MediaHub_Filter
{
    protected $query;
    protected $user;

    public function __construct(UNL_MediaHub_User $user)
    {
        $this->user = $user;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->addFrom('LEFT JOIN mediahub.media_text_tracks mtt ON (mtt.media_id = m.id)');
        $query->addFrom('JOIN mediahub.feed_has_media fhm ON (fhm.media_id = m.id)');
        $query->addFrom('JOIN mediahub.user_has_permission uhp ON (uhp.feed_id = fhm.feed_id)');

        // Where either:
        // 1. There is no caption track active on the media but there is an "ai transcriptionist" track available
        // 2. The active caption track is the original "ai transcriptionist" and not the reviewed version
        $query->where('
            (
                m.media_text_tracks_id IS NULL
                and mtt.source = "ai transcriptionist"
                and uhp.permission_id = 2
                and uhp.user_uid = ?
            )
            OR
            (
                m.media_text_tracks_id = mtt.id
                and mtt.source = "ai transcriptionist"
                and mtt.media_text_tracks_source_id is null
                and uhp.permission_id = 2
                and uhp.user_uid = ?
            )'
        , [$this->user->uid, $this->user->uid]);
        $query->groupBy('m.id');
    }
    
    public function getLabel()
    {
        return 'UnreviewedCaptions';
    }
    
    public function getType()
    {
        return 'UnreviewedCaptions';
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
        return 'List of the first 100 media uploads that either have an active AI caption that has not been reviewed or have a generated AI caption that is not yet activated and is pending review.';
    }
}
