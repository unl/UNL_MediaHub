<?php
class UNL_MediaHub_Manager_PostHandler
{
    public $options = array();
    public $post    = array();
    public $files   = array();
    public $mediahub;

    function __construct($options = array(),
                         $post    = array(),
                         $files   = array())
    {
        $this->options = $options;
        $this->post    = $post;
        $this->files   = $files;
    }

    function setMediaHub(UNL_MediaHub $mediahub)
    {
        $this->mediahub = $mediahub;
    }

    function handle()
    {
        $postTarget = $this->determinePostTarget();

        $this->filterPostData();

        switch ($postTarget) {
        case 'feed':
            if (isset($this->files['image_file'])
                && is_uploaded_file($this->files['image_file']['tmp_name'])) {
                $this->post['image_data'] = file_get_contents($this->files['image_file']['tmp_name']);
                $this->post['image_type'] = $this->files['image_file']['type'];
                $this->post['image_size'] = $this->files['image_file']['size'];
            }

            // Insert or update a Feed/Channel
            if (isset($this->post['id'])) {
                // Update an existing feed.
                $feed = UNL_MediaHub_Feed::getById($this->post['id']);
                $feed->synchronizeWithArray($this->post);
                $feed->save();
            } else {
                // Add a new feed for this user.
                $feed = UNL_MediaHub_Feed::addFeed($this->post, UNL_MediaHub_Manager::getUser());
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'feed_media':
            // Add media to a feed/channel
            if (isset($this->post['id'])) {
                // Editing media details
                $media = UNL_MediaHub_Media::getById($this->post['id']);
            } else {
                // Insert a new piece of media
                $details = array('url'        => $this->post['url'],
                                 'title'      => $this->post['title'],
                                 'description'=> $this->post['description']);
                $media = $this->mediahub->addMedia($details);
            }

            // Save details
            $media->synchronizeWithArray($this->post);
            $media->save();

            if (!empty($this->post['feed_id'])) {
                if (is_array($this->post['feed_id'])) {
                    $feed_ids = array_keys($this->post['feed_id']);
                } else {
                    $feed_ids = array($this->post['feed_id']);
                }
                foreach ($feed_ids as $feed_id) {
                    $feed = UNL_MediaHub_Feed::getById($feed_id);
                    if (!$feed->userHasPermission(UNL_MediaHub_Manager::getUser(),
                                                  UNL_MediaHub_Permission::getByID(
                                                    UNL_MediaHub_Permission::USER_CAN_INSERT))) {
                        throw new Exception('You do not have permission to do this.');
                    }
                    $feed->addMedia($media);
                }
            }
            
            if (!empty($this->post['new_feed'])) {
                $data = array('title'       => $this->post['new_feed'],
                              'description' => $this->post['new_feed']);
                $feed = UNL_MediaHub_Feed::addFeed($data, UNL_MediaHub_Manager::getUser());
                $feed->addMedia($media);
            }
            
            if (isset($feed, $feed->id)) {
                $this->redirect('?view=feed&id='.$feed->id);
            }
            // @todo clean cache for this feed!
            $this->redirect(UNL_MediaHub_Manager::getURL());
            break;
        case 'feed_users':
            $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
            if (!$feed->userHasPermission(UNL_MediaHub_Manager::getUser(),
                                          UNL_MediaHub_Permission::getByID(
                                            UNL_MediaHub_Permission::USER_CAN_ADD_USER))) {
                throw new Exception('You do not have permission to add a user.');
            }
            if (!empty($this->post['uid'])) {
                if (!empty($this->post['delete'])) {
                    $feed->removeUser(UNL_MediaHub_User::getByUid($this->post['uid']));
                } else {
                    $feed->addUser(UNL_MediaHub_User::getByUid($this->post['uid']));
                }
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'delete_media':
            $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
            $media = UNL_MediaHub_Media::getById($this->post['media_id']);
            if ($feed->hasMedia($media)
                && $feed->userHasPermission(UNL_MediaHub_Manager::getUser(),
                                            UNL_MediaHub_Permission::getByID(
                                                UNL_MediaHub_Permission::USER_CAN_DELETE))) {
                $media->delete();
            }
            $this->redirect('?view=feed&id='.$feed->id);
        }
    }

    /**
     * Determine what type of data is being saved.
     *
     * @return string
     */
    function determinePostTarget()
    {
        if (isset($this->post['__unlmy_posttarget'])) {
            return $this->post['__unlmy_posttarget'];
        }
        return false;
    }

    /**
     * Remove POST data that should not be handled.
     *
     * @return void
     */
    function filterPostData()
    {
        /** Remove linked records if they are not set anymore **/
        foreach (array('UNL_MediaHub_Feed_NamespacedElements_itunes'        => 'value',
                       'UNL_MediaHub_Feed_NamespacedElements_media'         => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunesu' => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunes'  => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_media'   => 'value') as $relation=>$field) {
            if (isset($this->post[$relation])) {
                foreach ($this->post[$relation] as $key=>$values) {
                    if (empty($values[$field])
                        && empty($values['attributes'])) {
                        unset($this->post[$relation][$key]);
                    }
                }
            }
        }
        unset($this->post['__unlmy_posttarget']);
        unset($this->post['MAX_FILE_SIZE']);
        unset($this->post['submit_existing']);
    }

    /**
     * Redirect to the location given.
     *
     * @param string $location URL to redirect to.
     */
    function redirect($location)
    {
        header('Location: '.$location);
        exit();
    }
}