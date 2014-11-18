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

        /**
         * Sort the feed elements so that their elements are ALWAYS
         * alphabetical.  There is a bug in Doctrine that will try
         * to save duplicate primary keys if they are not.
         */
        $this->sortPostFeedElements();
    }
    
    /**
     * Sorts feed elements in the post attribute so that the elements are listed
     * alphabetically.
     * 
     * @return null
     */
    private function sortPostFeedElements()
    {
        foreach($this->post as $key=>$value) {
            if (is_array($value) && strpos($key, 'UNL_MediaHub_Feed') !== false) {
                usort($value, array($this,'comparePostFeedElements'));
                $this->post[$key] = $value;
            }
        }
    }
    
    /**
     * Compares two feed elements
     * 
     * @param $a the first element to compare
     * @param $b the second elemenet to compare
     * 
     * @return int 1 if greater, -1 if less than, 0 if the same.
     */
    private function comparePostFeedElements($a, $b)
    {
        //We must be compairing arrays here.
        if (!(is_array($a) && is_array($b))) {
            return 0;
        }
        
        //element must be defined inorder to sort.
        if (!(isset($a['element']) && isset($b['element']))) {
            return 0;
        }
        
        if ($a['element'] == $b['element']) {
            return 0;
        }
        
        return ($a['element'] < $b['element']) ? -1 : 1;
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
        if ($url = $this->_handleMediaFileUpload()) {
            $this->redirect(UNL_MediaHub_Manager::getURL().'?view=uploadcomplete&format=json&url='.urlencode($url));
        }
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
        // Settings
        $targetDir = UNL_MediaHub_Manager::getTmpUploadDirectory();
        //$targetDir = 'uploads';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds (5 hours)
        
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($this->post['name'])) {
            $fileName = $this->post['name'];
        } elseif (!empty($this->files)) {
            $fileName = $this->files['file']['name'];
        } else {
            $fileName = uniqid('file_');
        }
        
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($this->post["chunk"]) ? intval($this->post["chunk"]) : 0;
        $chunks = isset($this->post["chunks"]) ? intval($this->post["chunks"]) : 0;
        
        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to open temp directory.', 500);
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to open output stream.', 500);
        }

        if (!empty($this->files)) {
            if ($this->files["file"]["error"] || !is_uploaded_file($this->files["file"]["tmp_name"])) {
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to move uploaded file.', 500);
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($this->files["file"]["tmp_name"], "rb")) {
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to open input stream.', 500);
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to open input stream.', 500);
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            //3gp doesnt work with mediaelement. Right now call it an mp4 (won't always work because 3gps are not always h264.)
            //TODO: Handle this better.  perhaps check the file encoding or convert it instead of just renaming it.
            if ($extension == '3gp') {
                $extension = 'mp4';
            }

            $finalName = md5(microtime() + rand()) . '.'. $extension;
            $finalPath = UNL_MediaHub_Manager::getUploadDirectory() . DIRECTORY_SEPARATOR . $finalName;
            
            // Strip the temp .part suffix off 
            rename("{$filePath}.part", $finalPath);
            return UNL_MediaHub_Controller::$url.'uploads/'.$finalName;
        }

        return false;
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
        // Check for required fields
        if (empty($this->post['url'])) {
            throw new Exception('Please provide a URL for this media.', 400);
        }
        
        if (!filter_var($this->post['url'], FILTER_VALIDATE_URL)) {
            throw new Exception('The provided value for field "url" is invalid.  It must be a valid absolute URL.', 400);
        }

        if (empty($this->post['title'])) {
            throw new Exception('Please provide a title for this media.', 400);
        }

        if (empty($this->post['author'])) {
            throw new Exception('Please provide an author for this media.', 400);
        }

        if (empty($this->post['description'])) {
            throw new Exception('Please provide a description for this media.', 400);
        }

        if (empty($this->post['feed_id'])) {
            throw new Exception('Please pick which channel(s) this media should be associated with.', 400);
        }
        
        if (!isset($this->post['feed_id']) && empty($this->post['new_feed'])) {
            throw new Exception('You must select a feed for the media', 400);
        }

        // Add media to a feed/channel
        if (isset($this->post['id'])) {
            // Editing media details
            $media = UNL_MediaHub_Media::getById($this->post['id']);
        } else {
            // Insert a new piece of media
            $details = array(
                'url'        => $this->post['url'],
                'title'      => $this->post['title'],
                'description'=> $this->post['description'],
                'author'     => $this->post['author'],
            );
                             
            $media = $this->mediahub->addMedia($details);
        }
        
        // Save details
        $media->synchronizeWithArray($this->post);
        
        $media->save();

        if (!empty($this->post['feed_id'])) {
            if (is_array($this->post['feed_id'])) {
                $feed_ids = $this->post['feed_id'];
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