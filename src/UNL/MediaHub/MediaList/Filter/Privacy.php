<?php
class UNL_MediaHub_MediaList_Filter_Privacy implements UNL_MediaHub_Filter
{
    function apply(Doctrine_Query &$query)
    {
        $user = UNL_MediaHub_Controller::getUser();
        
        if ($user) {
            $feeds = $user->getFeeds();
            $feeds->run();

            $feeds_array = array();
            foreach ($feeds->items as $feed) {
                $feeds_array[] = $feed->id;
            }
            
            $query->andWhere('m.privacy = \'PUBLIC\' OR m.UNL_MediaHub_Feed_Media.feed_id IN (' . implode(',', $feeds_array) .')');
        } else {
            $query->andWhere('m.privacy = \'PUBLIC\'');
        }
        
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
