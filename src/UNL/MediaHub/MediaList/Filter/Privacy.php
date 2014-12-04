<?php
class UNL_MediaHub_MediaList_Filter_Privacy implements UNL_MediaHub_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $where = 'm.privacy = "PUBLIC"';
        
        $user = UNL_MediaHub_Controller::getUser();
        
        if ($user) {
            $feeds = $user->getFeedIDs();
            
            if (!empty($feeds)) {
                //There is a chance that a user will not have any feeds, so account for that.
                $where = 'm.privacy = "PUBLIC" OR m.UNL_MediaHub_Feed_Media.feed_id IN (' . implode(',', $feeds) .')';
            }
        }
        
        $query->andWhere($where);
    }

    function getLabel()
    {
        return 'Privacy';
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
        return 'Show only media that the current user has access to';
    }
}
