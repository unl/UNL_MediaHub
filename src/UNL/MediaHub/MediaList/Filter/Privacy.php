<?php
class UNL_MediaHub_MediaList_Filter_Privacy implements UNL_MediaHub_NativeSqlFilter
{
    /**
     * @var UNL_MediaHub_User
     */
    protected $user = NULL;
    
    function __construct(UNL_MediaHub_User $user = NULL)
    {
        $this->user = $user;
    }

    /**
     * The end result should look something like this (depending on other filters)
     *
     * Native language eqv: "get all media which is either public or in one of your feeds"
     * 
     SELECT DISTINCT m2.id
     FROM media m2
     LEFT JOIN (
       # media in feeds user can see
       SELECT media_id
       FROM feed_has_media
       WHERE feed_id IN (82, 111, 311, 319, 600, 1575, 1587)
       GROUP BY media_id
     ) AS f2 ON m2.id = f2.media_id
     WHERE
       (m2.privacy = 'PUBLIC'
       OR f2.media_id IS NOT NULL)
     ORDER BY m2.datecreated DESC
     * 
     * @param Doctrine_RawSql $query
     */
    function apply(Doctrine_RawSql &$query)
    {
        $where = '(';
        
        if (UNL_MediaHub_Controller::$caption_requirement_date) {
            //Captions are not required; exit early.
            $where .= 'm.privacy = "PUBLIC" AND (m.media_text_tracks_id IS NOT NULL OR m.datecreated < "' . UNL_MediaHub_Controller::$caption_requirement_date . '")';
        } else {
            $where .= 'm.privacy = "PUBLIC"';
        }
        
        if ($this->user) {
            $feeds = $this->user->getFeedIDs();
            
            if (!empty($feeds)) {
                $feeds = implode(',', $feeds);

                $query->addFrom('
                  LEFT JOIN (
                    SELECT media_id
                    FROM feed_has_media
                    WHERE feed_id IN ('.$feeds.')
                    GROUP BY media_id
                  ) fm ON (fm.media_id = m.id)'
                );
                
                //There is a chance that a user will not have any feeds, so account for that.
                $where .= ' OR fm.media_id IS NOT NULL';
            }
        }

        $where .= ')';

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
