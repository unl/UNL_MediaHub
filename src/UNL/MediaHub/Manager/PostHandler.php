<?php
/**
 * Class which handles post data and media uploads
 * @author bbieber
 */
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

    /**
     * Set the mediahub instance
     *
     * @param UNL_MediaHub $mediahub
     */
    function setMediaHub(UNL_MediaHub $mediahub)
    {
        $this->mediahub = $mediahub;
    }

    /**
     * Process data sent through the request
     *
     * @return void
     */
    function handle()
    {
        $this->verifyPost();

        $postTarget = $this->determinePostTarget();

        $this->filterPostData();

        switch ($postTarget) {
        case 'upload_media':
            $this->handleMediaFileUpload();
            break;
        case 'feed':
            $this->handleFeed();
            break;
        case 'feed_media':
            $this->handleFeedMedia();
            break;
        case 'feed_users':
            $this->handleFeedUsers();
            break;
        case 'delete_media':
            $this->handleDeleteMedia();
            break;
        }
    }

    /**
     * Verify the server captured all the data sent by the client
     *
     * @throws UNL_MediaHub_Manager_PostHandler_UploadException
     * @return void
     */
    function verifyPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST'
            && empty($this->post)
            && empty($this->files)
            && isset($_SERVER['CONTENT_LENGTH'])
            && $_SERVER['CONTENT_LENGTH'] > 0 ) {

            $maxSize = ini_get('post_max_size');

            switch (substr($maxSize,-1)){
            case 'G':
                $maxSize = $maxSize * 1024;
            case 'M':
                $maxSize = $maxSize * 1024;
            case 'K':
                $maxSize = $maxSize * 1024;
            }
            throw new UNL_MediaHub_Manager_PostHandler_UploadException('Sorry, the amount of data POSTed exceeded the maximum amount ('.$maxSize.' bytes)', 413);
        }
    }

    /**
     * Handles new media file uploads
     *
     * @return void
     */
    public function handleMediaFileUpload()
    {

        $url = $this->_handleMediaFileUpload();
        $this->redirect(UNL_MediaHub_Manager::getURL().'?view=uploadcomplete&format=barebones&url='.urlencode($url));

    }

    /**
     * Handles new media file uploads
     * 
     * Copies any files posted to the uploads directory, with a unique filename.
     * 
     * After the file has been saved, the URL to the media is returned.
     * 
     * @see UNL_MediaHub_Manager::getUploadDirectory()
     * 
     * @return string URL to media
     */
    protected function _handleMediaFileUpload()
    {

        if (empty($this->files)
            || !isset($this->files['file_upload'])) {
            // nothing to do
            return false;
        }

        if ($this->files['file_upload']['error'] != UPLOAD_ERR_OK) {
            throw new UNL_MediaHub_Manager_PostHandler_UploadException($this->files['file_upload']['error'], 500);
        }

        // Verify extension
        if (!self::validMediaFileName($this->files['file_upload']['name'])) {
            throw new UNL_MediaHub_Manager_PostHandler_UploadException('Invalid file extension uploaded '.$this->files['file_upload']['name'], 500);
        }

        $extension = strtolower(pathinfo($this->files['file_upload']['name'], PATHINFO_EXTENSION));

        //3gp doesnt work with mediaelement. Right now call it an mp4 (won't always work because 3gps are not always h264.)
        //TODO: Handle this better.  perhaps check the file encoding or convert it instead of just renaming it.
        if ($extension == '3gp') {
            $extension = 'mp4';
        }
        
        $filename = md5(microtime() + rand()) . '.'. $extension;

        // Copy file to uploads diretory
        if (false == copy($this->files['file_upload']['tmp_name'],
                          UNL_MediaHub_Manager::getUploadDirectory()
                          . DIRECTORY_SEPARATOR .$filename)) {
            throw new UNL_MediaHub_Manager_PostHandler_UploadException('Error copying file from temp location to permanent location', 500);
        }

        return UNL_MediaHub_Controller::$url.'uploads/'.$filename;
    }

    /**
     * Checks if the filename is supported.
     * 
     * @param string $filename Filename to check
     * 
     * @return bool
     */
    public static function validMediaFileName($filename)
    {
        $allowedExtensions = array('mp4', 'm4v', 'mp3', 'ogg', '3gp');
        return in_array(end(explode('.', strtolower($filename))), $allowedExtensions);
    }

    /**
     * Saves meta data for feeds
     *
     * @return void
     */
    function handleFeed()
    {
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
    }

    /**
     * Handle adding media, and associating it to feeds
     *
     * @throws Exception
     */
    function handleFeedMedia()
    {
        // Check if a file was uploaded
        if (empty($this->post['url'])
            && !empty($this->files)) {
            $this->post['url'] = $this->_handleMediaFileUpload();
        }

        if (empty($this->post['url'])) {
            throw new Exception('Either no URL was submitted, or your file upload failed', 400);
        }

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
                if (!$feed->userHasPermission(
                        UNL_MediaHub_Manager::getUser(),
                        UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_INSERT)
                        )
                    ) {
                    throw new Exception('You do not have permission to do this.', 403);
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
    }

    /**
     * Adding users to a channel/feed
     *
     * @throws Exception
     */
    function handleFeedUsers()
    {
        $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
        if (!$feed->userHasPermission(
                UNL_MediaHub_Manager::getUser(),
                UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_ADD_USER)
                )
            ) {
            throw new Exception('You do not have permission to add a user.', 403);
        }
        if (!empty($this->post['uid'])) {
            if (!empty($this->post['delete'])) {
                $feed->removeUser(UNL_MediaHub_User::getByUid($this->post['uid']));
            } else {
                $feed->addUser(UNL_MediaHub_User::getByUid($this->post['uid']));
            }
        }
        $this->redirect('?view=feed&id='.$feed->id);
    }

    /**
     * Delete specific media
     *
     * @return void
     */
    function handleDeleteMedia()
    {
        $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);
        if ($feed->hasMedia($media)
            && $feed->userHasPermission(
                    UNL_MediaHub_Manager::getUser(),
                    UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_DELETE)
                )
            ) {
            $media->delete();
        }
        $this->redirect('?view=feed&id='.$feed->id);
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
        foreach (array('UNL_MediaHub_Feed_NamespacedElements_itunes'         => 'value',
                       'UNL_MediaHub_Feed_NamespacedElements_media'          => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunesu'  => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunes'   => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_media'    => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_geo'      => 'value',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_mediahub' => 'value') as $relation=>$field) {
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