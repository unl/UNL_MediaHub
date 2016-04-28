<?php
class UNL_MediaHub_FeedList_Filter_ByUser implements UNL_MediaHub_Filter
{
    protected $user;
    
    public function __construct(UNL_MediaHub_User $user)
    {
        $this->user = $user;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->where('UNL_MediaHub_User_Permission.user_uid = ? AND UNL_MediaHub_User_Permission.feed_id = f.id', $this->user->uid);
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
