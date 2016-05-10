<?php
class UNL_MediaHub_FeedList_Filter_ByUserWithMediaId implements UNL_MediaHub_Filter
{
    protected $user;
    protected $mediaId;

    public function __construct(UNL_MediaHub_User $user, $mediaId)
    {
        $this->user = $user;
        $this->mediaId = $mediaId;
    }

    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('UNL_MediaHub_User_Permission.user_uid = ? AND UNL_MediaHub_User_Permission.feed_id = f.id
                       AND UNL_MediaHub_Feed_Media.media_id = ? AND UNL_MediaHub_Feed_Media.feed_id = f.id',
            array($this->user->uid, $this->mediaId));
        
        $query->distinct();
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
}
