<?php
class UNL_MediaHub_FeedList_Filter_ByUserWithMediaId implements UNL_MediaHub_Filter
{
    protected $user;
    protected $mediaId;

    function __construct(UNL_MediaHub_User $user, $mediaId)
    {
        $this->user = $user;
        $this->mediaId = $mediaId;
    }

    function apply(Doctrine_Query &$query)
    {
        $query->where('UNL_MediaHub_User_Permission.user_uid = ? AND UNL_MediaHub_User_Permission.feed_id = f.id
                       AND UNL_MediaHub_Feed_Media.media_id = ? AND UNL_MediaHub_Feed_Media.feed_id = f.id',
            array($this->user->uid, $this->mediaId));
        
        $query->distinct();
    }

    function getLabel()
    {
        return '';
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
}