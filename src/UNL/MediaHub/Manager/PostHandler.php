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
    protected $controller;
    public $mediahub;

    function __construct(\UNL_MediaHub_BaseController $controller,
                         $options = array(),
                         $post    = array(),
                         $files   = array())
    {
        $this->options = $options;
        $this->post    = $post;
        $this->files   = $files;
        $this->controller = $controller;

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

        $verify_csrf = true;
        if ($verify_csrf && !$this->controller->validateCSRF()) {
            throw new \Exception('Invalid security token provided. If you think this was an error, please retry the request.', 403);
        }

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
        case 'delete_feed':
            $this->handleDeleteFeed();
            break;
        case 'copy_text_track_file':
            $this->handleCopyTextTrackFile();
            break;
        case 'copy_and_edit_text_track_file':
            $this->handleCopyAndEditTextTrackFile();
            break;
        case 'update_text_track_file';
            $this->handleUpdateTextTrackFile();
            break;
        case 'save_review_ai_captions';
            $this->handleAICaptionReview();
            break;
        case 'delete_text_track_file';
            $this->handleDeleteTextTrackFile();
            break;
        case 'pull_amara':
            $this->handleAmara();
            break;
        case 'order_rev':
            $this->handleRev();
            break;
        case 'ai_captions':
            $this->handleTranscriptions();
            break;
        case 'ai_captions_retry':
            $user = UNL_MediaHub_AuthService::getInstance()->getUser();
            if ($user->isAdmin()) {
                $this->handleTranscriptions();
            }
            break;
        case 'download_rev':
            $this->downloadRev();
            break;
        case 'set_active_text_track':
            $this->setActiveTextTrack();
            break;
        case 'retry_transcoding_job':
            $this->retryTranscodingJob();
            break;
        case 'upload_caption_file':
            $this->uploadCaptionFile();
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
            UNL_MediaHub::redirect(UNL_MediaHub_Manager::getURL().'?view=uploadcomplete&format=json&url='.urlencode($url));
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
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Failed to move uploaded file. Max upload or post size is likely too small.', 500);
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
            
            //Make sure that the media has a valid extension
            $allowed_extensions = array('mp4', 'mp3', 'mov');
            if (!in_array($extension, $allowed_extensions)) {
                //Remove the file
                unlink("{$filePath}.part");
                
                //throw the error
                throw new UNL_MediaHub_Manager_PostHandler_UploadException('Invalid extension', 400);
            }

            $finalName = md5(uniqid()) . '.'. $extension;
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
            $feed = UNL_MediaHub_Feed::addFeed($this->post, UNL_MediaHub_AuthService::getInstance()->getUser());
        }
        UNL_MediaHub::redirect('?view=feedmetadata&id='.$feed->id);
    }

    private function validateFeedMediaRequired() {
        // Check for required fields
        if (empty($this->post['title'])) {
            throw new Exception('Please provide a title for this media.', 400);
        }

        if (empty($this->post['author'])) {
            throw new Exception('Please provide an author for this media.', 400);
        }

        if (empty($this->post['description'])) {
            throw new Exception('Please provide a description for this media.', 400);
        }

        if (!isset($this->post['feed_id']) && empty($this->post['new_feed'])) {
            throw new Exception('You must select a feed for the media', 400);
        }
    }

    /**
     * Handle adding media, and associating it to feeds
     *
     * @throws Exception
     */
    function handleFeedMedia()
    {
        $this->validateFeedMediaRequired();

        $is_new = false;

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        // Add media to a feed/channel
        if (isset($this->post['id'])) {
            // Editing media details
            $media = UNL_MediaHub_Media::getById($this->post['id']);
            
            if (!$media->userCanEdit($user)) {
                throw new Exception('You do not have permission to edit this media.', 403);
            }
            
            $media->uidupdated = $user->uid;

            //Check if new media was uploaded (the url changed)
            if(!empty($this->post['url']) && $media->url != $this->post['url']) {
                $local_file = $media->getLocalFileName();
                $new_local_file = UNL_MediaHub_Media::getLocalFileNameByURL($this->post['url']);

                if ($local_file && !is_dir($local_file) && $new_local_file) {
                    //Both files are local.
                    rename($new_local_file, $local_file); //Replace the old one (keeping its name).
                    $this->post['url'] = $media->url; //Don't update the URL of the file
                } else if ($local_file && !is_dir($local_file)) {
                    //New file is not local, but old one is. Delete the old one.
                    unlink($local_file);
                }

                $transcoding_job = $media->getMostRecentTranscodingJob();
                if ($transcoding_job) {
                    //re-transcode this version if the previous job was also transcoded
                    $media->transcode($transcoding_job->job_type);
                }
            }
        } else {
            // Insert a new piece of media
            // The url is required here
            if (empty($this->post['url'])) {
                throw new Exception('Please provide a URL for this media.', 400);
            }

            if (!filter_var($this->post['url'], FILTER_VALIDATE_URL)) {
                throw new Exception('The provided value for field "url" is invalid.  It must be a valid absolute URL.', 400);
            }

            $details = array(
                'url'        => $this->post['url'],
                'title'      => $this->post['title'],
                'description'=> $this->post['description'],
                'author'     => $this->post['author'],
                'uidcreated' => $user->uid,
            );

            $media = $this->mediahub->addMedia($details);
            $is_new = true;
        }

        if (isset($this->post['remove_poster_image'])) {
            $media->removeLocalPosterFile();
        }

        if (isset($this->files['poster_image_file']) && is_uploaded_file($this->files['poster_image_file']['tmp_name'])) {
            $media->processPosterUpload($this->files['poster_image_file'], $errors);
            if (is_array($errors) && count($errors)) {
                throw new UNL_MediaHub_RuntimeException($errors[0], 400);
            }
        } else {
            $this->post['poster'] = $this->post['poster_image_url'];
        }

        //Update the dateupdated date for cache busting
        $media->dateupdated = date('Y-m-d H:i:s');
        
        if (empty($this->post['url'])) {
            //Remove the url from the post array so that `synchronizeWithArray` doesn't save a null value to the db.
            unset($this->post['url']);
        }
        
        // Save details
        $media->synchronizeWithArray($this->post);

        if ($duration = $media->findDuration()) {
            //Save the duration
            $media->duration = $duration->getTotalMilliseconds();
        }
        
        $media->save();

        if (isset($this->post['projection'])) {
            $media->setProjection($this->post['projection']);
        }
        
        $poster_file = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/'.$media->id.'/original.jpg';
        if (!empty($media->poster) && file_exists($poster_file)) {
            //We are referencing a custom poster, so delete the existing one if it exists
            unlink($poster_file);
        }

        if (!empty($this->post['feed_id'])) {
            $feed_selector = new UNL_MediaHub_Feed_Media_FeedSelection(UNL_MediaHub_AuthService::getInstance()->getUser(), $media);
            $feed_selection_data = $feed_selector->getFeedSelectionData();
            
            if (is_array($this->post['feed_id'])) {
                $feed_ids = $this->post['feed_id'];
            } else {
                $feed_ids = array($this->post['feed_id']);
            }
            
            //Add feeds
            foreach ($feed_ids as $feed_id) {
                $feed = UNL_MediaHub_Feed::getById($feed_id);
                
                //Make sure it is in the list of current/available feeds
                if (!isset($feed_selection_data[$feed_id])) {
                    throw new Exception('Feed ' .$feed->title . ' can not be selected by you.', 403);
                }

                //Check if it was already added
                if ($feed_selection_data[$feed_id]['selected']) {
                    continue;
                }

                //Make sure that they can add the feed
                if ($feed_selection_data[$feed_id]['readonly']) {
                    throw new Exception('Feed ' .$feed->title . ' can not be selected by you.', 403);
                }
                $feed->addMedia($media);
            }
            
            //Remove Feeds
            foreach ($feed_selection_data as $feed_id => $data) {
                //Check if it needs to be removed
                if (in_array($feed_id, $feed_ids)) {
                    continue;
                }

                //If it was not already selected, there is no need to remove it
                if (!$data['selected']) {
                    continue;
                }
                
                //Make sure that they can remove the feed
                if ($data['readonly']) {
                    throw new Exception('Feed ' .$data['feed']->title . ' can not be removed by you.', 403);
                }
                $data['feed']->removeMedia($media);
            }
        }

        if (!empty($this->post['new_feed'])) {
            $data = array('title'       => $this->post['new_feed'],
                          'description' => $this->post['new_feed']);
            $feed = UNL_MediaHub_Feed::addFeed($data, UNL_MediaHub_AuthService::getInstance()->getUser());
            $feed->addMedia($media);
        }

        if ($is_new) {
            // Tries to make transcoding job
            $transcoding_successful = true;
            $transcode_output = $media->transcode('hls');
            if ($transcode_output === false || !$media->getMostRecentTranscodingJob()) {
                $transcoding_successful = false;
            }

            // Tries to make a transcription job
            $transcribing_successful = true;
            $captions_opt_out = isset($this->post['opt-out-captions']) && $this->post['opt-out-captions'] === '1';
            $auto_activate = isset($this->post['auto-activate-captions']) && $this->post['auto-activate-captions'] === '1';

            if ($captions_opt_out) {
                $transcribing_successful = false;
            } else {
                try {
                    // Set up variable for transcriber
                    $media_url = $media->getURL() . '/file';
    
                    // Called API to make job
                    $ai_captioning = new UNL_MediaHub_TranscriptionAPI();
                    $job_id = $ai_captioning->create_job($media_url);
                    if ($job_id === false) {
                        throw new Exception('Could Not Create New Job', 500);
                    }

                    // If successful it will create a job in the database
                    $media->transcription($job_id, $user->uid, $auto_activate);
                } catch(Exception $e) {
                    $transcribing_successful = false;
                }
            }

            // Creates the success message string based on $transcribing_successful,
            //   $transcribing_successful, and UNL_MediaHub_Controller::$caption_requirement_date
            $success_string = "";
            if ($transcribing_successful && $transcoding_successful) {
                $success_string = 'Your media has been uploaded and is being optimized and captioned. ';
                $success_string .= 'Once we get those finished it will be published. ';
            } elseif ($transcribing_successful) {
                $success_string = 'Your media has been uploaded and is being captioned. ';
                $success_string .= 'Once we get that finished it will be published. ';
            } elseif ($transcoding_successful) {
                $success_string = 'Your media has been uploaded and is being optimized. ';
                if (UNL_MediaHub_Controller::$caption_requirement_date !== false) {
                    $success_string .= 'Your media will not be published until it is captioned. ';
                } else {
                    $success_string .= 'Once we get that finished it will be published. ';
                    $success_string .= 'Please make sure that the media is captioned. ';
                }
            } else {
                if (UNL_MediaHub_Controller::$caption_requirement_date !== false) {
                    $success_string = 'Your media will not be published until it is captioned. ';
                } else {
                    $success_string = 'Your media has been uploaded and is now published. ';
                    $success_string .= 'Please make sure that the media is captioned. ';
                }
            }

            // Sends notice to page
            $notice = new UNL_MediaHub_Notice(
                'Success',
                $success_string,
                UNL_MediaHub_Notice::TYPE_SUCCESS
            );
            UNL_MediaHub_Manager::addNotice($notice);
            
            UNL_MediaHub::redirect($media->getEditCaptionsURL());
        } else {
            $notice = new UNL_MediaHub_Notice(
                'Success',
                'The media has been updated',
                UNL_MediaHub_Notice::TYPE_SUCCESS
            );
            UNL_MediaHub_Manager::addNotice($notice);
            
            $last_job = $media->getMostRecentTranscodingJob();
            
            if ($last_job && !$last_job->isFinished()) {
                UNL_MediaHub::redirect(UNL_MediaHub_Manager::getURL() . '?view=addmedia&id='.$media->id);
            } else {
                UNL_MediaHub::redirect(UNL_MediaHub_Controller::getURL($media));
            }
        }
    }

    /**
     * Adding users to a channel/feed
     *
     * @throws Exception
     */
    function handleFeedUsers()
    {
        $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
        if (!$user->isAdmin()) {
            if (!$feed->userHasPermission(
                    $user,
                    UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_ADD_USER)
                    )
                ) {
                throw new Exception('You do not have permission to add a user.', 403);
            }
        }
        if (!empty($this->post['uid'])) {
            if (!empty($this->post['delete'])) {
                $feed->removeUser(UNL_MediaHub_User::getByUid($this->post['uid']));
            } else {
                $feed->addUser(UNL_MediaHub_User::getByUid(trim($this->post['uid'])));
            }
        }
        UNL_MediaHub::redirect('?view=permissions&feed_id='.$feed->id);
    }

    /**
     * Delete specific media
     *
     * @throws Exception
     * @return void
     */
    function handleDeleteFeed()
    {
        if (empty($this->post['feed_id'])) {
            throw new Exception('Please provide a feed id to delete.', 400);
        }
        
        $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        $feed = UNL_MediaHub_Feed::getById($this->post['feed_id']);
        
        if (!$user->isAdmin()) {
            if (!$feed->userHasPermission(
                    UNL_MediaHub_AuthService::getInstance()->getUser(), 
                    UNL_MediaHub_Permission::getByID(UNL_MediaHub_Permission::USER_CAN_DELETE
                ))) {
                throw new Exception('You do not have permission to delete this.', 403);
            }
        }

        $feed->delete();

        UNL_MediaHub::redirect(UNL_MediaHub_Manager::getURL());
    }

    /**
     * Delete specific media
     *
     * @throws Exception
     * @return void
     */
    function handleDeleteMedia()
    {
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);

        if (!$media->userHasPermission(UNL_MediaHub_AuthService::getInstance()->getUser(), UNL_MediaHub_Permission::USER_CAN_DELETE)) {
            throw new Exception('You do not have permission to delete this.', 403);
        }

        $media->delete();

        UNL_MediaHub::redirect(UNL_MediaHub_Manager::getURL());
    }

    function handleDeleteTextTrackFile() {
        $mediaId = !empty($this->post['media_id']) ? $this->post['media_id'] : 0;
        if (!$media = UNL_MediaHub_Media::getById($mediaId)) {
            throw new Exception('Unable to find media', 404);
        }

        if (!$media->userHasPermission(UNL_MediaHub_AuthService::getInstance()->getUser(), UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        $trackId = !empty($this->post['text_track_id']) ? $this->post['text_track_id'] : 0;
        if (!$track = UNL_MediaHub_MediaTextTrack::getById($trackId)) {
            throw new \Exception('Could not find that track', 404);
        }

        if ($media->id != $track->media_id) {
            throw new \Exception('Track Media Invalid', 404);
        }

        if ($media->media_text_tracks_id == $track->id) {
            throw new \Exception('Active track cannot be deleted', 404);
        }

        // Attempt to copy track and track file
        try {
            // delete track
            $track->delete();
        } catch(Exception $e) {
            if (!empty($newTrack->id)) {
                $newTrack->delete();
            }
            throw new \Exception('Error deleting caption track', 404);
        }

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'The caption track has been deleted.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }

    public function subFuncCopyTextTrackFile($comment_text=null) {
        $mediaId = !empty($this->post['media_id']) ? $this->post['media_id'] : 0;
        if (!$media = UNL_MediaHub_Media::getById($mediaId)) {
            throw new Exception('Unable to find media', 404);
        }

        if (!$media->userHasPermission(UNL_MediaHub_AuthService::getInstance()->getUser(), UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        $trackId = !empty($this->post['text_track_id']) ? $this->post['text_track_id'] : 0;
        if (!$track = UNL_MediaHub_MediaTextTrack::getById($trackId)) {
            throw new \Exception('Could not find that track', 404);
        }

        if ($media->id != $track->media_id) {
            throw new \Exception('Track Media Invalid', 404);
        }

        $trackFiles = $track->getFiles()->items;
        $trackFile = isset($trackFiles[0]) ? $trackFiles[0] : NULL;
        if (empty($trackFile)) {
            throw new \Exception('Track missing track file');
        }

        if ($track->id != $trackFile->media_text_tracks_id) {
            throw new \Exception('Track File Invalid', 404);
        }

        // Attempt to copy track and track file
        try {
            // copy track
            $newTrack = new UNL_MediaHub_MediaTextTrack();
            $newTrack->media_id = $track->media_id;
            $newTrack->source = $track->source;

            if ($comment_text === null) {
                $newTrack->revision_comment = 'Copied from track id ' . $track->id;
            } else {
                $newTrack->revision_comment = $comment_text;
            }
            $newTrack->media_text_tracks_source_id = $track->id;
            $newTrack->save();

            // copy track file
            $newTrackFile = new UNL_MediaHub_MediaTextTrackFile();
            $newTrackFile->media_text_tracks_id = $newTrack->id;
            $newTrackFile->kind = $trackFile->kind;
            $newTrackFile->format = $trackFile->format;
            $newTrackFile->language = $trackFile->language;
            $newTrackFile->file_contents = $trackFile->file_contents;
            $newTrackFile->save();
        } catch(Exception $e) {
            if (!empty($newTrack->id)) {
                $newTrack->delete();
            }
            throw new \Exception('Error copying caption track', 404);
        }

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'The caption track has been copied.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        return [
            'editCaptionsURL' => $media->getEditCaptionsURL(),
            'mediaID' => $media->id,
            'media' => $media,
            'trackID' => $newTrack->id,
            'track' => $newTrack,
            'trackFile' => $newTrackFile
        ];
    }

    public function handleCopyTextTrackFile() {
        $returnData = $this->subFuncCopyTextTrackFile();
        UNL_MediaHub::redirect($returnData['editCaptionsURL']);
    }

    public function handleCopyAndEditTextTrackFile() {
        $returnData = $this->subFuncCopyTextTrackFile();

        $redirectURL = UNL_MediaHub_Manager::getURL()
        . '?view=editcaptiontrack&media_id='
        . (int)$returnData['mediaID']
        . '&track_id='
        . (int)$returnData['trackID'];

        UNL_MediaHub::redirect($redirectURL);
    }

    protected function uploadCaptionFile() {
        // Validate media
        $mediaId = !empty($this->post['media_id']) ? $this->post['media_id'] : 0;
        if (!$media = UNL_MediaHub_Media::getById($mediaId)) {
            throw new Exception('Unable to find media', 404);
        }

        // Double check user auth
        if (!$media->userHasPermission(
                UNL_MediaHub_AuthService::getInstance()->getUser(),
                UNL_MediaHub_Permission::USER_CAN_UPDATE
            )) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        // Double check we actually just uploaded this file
        if (!is_uploaded_file($_FILES['caption_file']['tmp_name'])) {
            throw new Exception('Invalid Caption File', 400);
        }

        // Validate file mime type
        $mimeType = mime_content_type($_FILES['caption_file']['tmp_name']);
        if ($mimeType !== 'text/plain') {
            throw new Exception('Invalid Caption File', 400);
        }

        // Validate file extension
        $fileExtension = explode(".", $_FILES['caption_file']['name']);
        $fileExtension = strtolower(end($fileExtension));
        $availableExtensions = array('vtt', 'srt');
        if (!in_array($fileExtension, $availableExtensions)) {
            throw new Exception('Invalid Caption File', 400);
        }

        // Try to read from file
        $fileContent = file_get_contents($_FILES['caption_file']['tmp_name']);
        if ($fileContent === false || empty($fileContent)) {
            throw new Exception('Invalid Caption File', 400);
        }

        // Validate language and country codes
        if (
            !isset($this->post['language'])
            || empty($this->post['language'])
            || strlen($this->post['language']) !== 2
        ) {
            throw new Exception('Missing or Invalid Language Code', 400);
        }
        if (
            isset($this->post['country'])
            && !empty($this->post['country'])
            && strlen($this->post['country']) !== 2
        ) {
            throw new Exception('Invalid Country Code', 400);
        }

        // Format language code
        $language_code = strtolower($this->post['language']);
        if (isset($this->post['country']) && !empty($this->post['country'])) {
            $language_code .= '-' . strtolower($this->post['country']);
        }

        $comment = null;
        if (isset($this->post['caption_comment']) && !empty($this->post['caption_comment'])) {
            $comment = $this->post['caption_comment'];
        }

        // Convert SRT files to vtt since srt files will error on `www/templates/html/MediaPlayer/Transcript.tpl.php`
        if ($fileExtension == 'srt') {
            $vtt_converter = new UNL_MediaHub_Manager_PostHandler_VttHelper($fileContent);
            $fileContent = $vtt_converter->get_vtt_file();
            $fileExtension = 'vtt';
        }

        // Attempt to copy track and track file
        try {
            // copy track
            $newTrack = new UNL_MediaHub_MediaTextTrack();
            $newTrack->media_id = $media->id;
            $newTrack->source = 'upload';
            $newTrack->revision_comment = $comment;
            $newTrack->save();

            // copy track file
            $newTrackFile = new UNL_MediaHub_MediaTextTrackFile();
            $newTrackFile->media_text_tracks_id = $newTrack->id;
            $newTrackFile->kind = 'caption';
            $newTrackFile->format = $fileExtension;
            $newTrackFile->language = $language_code;
            $newTrackFile->file_contents = $fileContent;
            $newTrackFile->save();
        } catch(Exception $e) {
            if (!empty($newTrack->id)) {
                $newTrack->delete();
            }
            throw new \Exception('Error copying caption track', 404);
        }

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'The caption track has been copied.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }

    function handleUpdateTextTrackFile() {
        $mediaId = !empty($this->post['media_id']) ? $this->post['media_id'] : 0;
        if (!$media = UNL_MediaHub_Media::getById($mediaId)) {
            throw new Exception('Unable to find media', 404);
        }

        if (!$media->userHasPermission(UNL_MediaHub_AuthService::getInstance()->getUser(), UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        $trackId = !empty($this->post['track_id']) ? $this->post['track_id'] : 0;
        if (!$track = UNL_MediaHub_MediaTextTrack::getById($trackId)) {
            throw new \Exception('Could not find that track', 404);
        }

        if ($media->id != $track->media_id) {
            throw new \Exception('Track Media Invalid', 404);
        }

        $trackFileId = !empty($this->post['track_file_id']) ? $this->post['track_file_id'] : 0;
        if (!$trackFile = UNL_MediaHub_MediaTextTrackFile::getById($trackFileId)) {
            throw new \Exception('Could not find that track file', 404);
        }

        if ($track->id != $trackFile->media_text_tracks_id) {
            throw new \Exception('Track File Invalid', 404);
        }

        if (empty($track->media_text_tracks_source_id)) {
            throw new \Exception('Track File must be a copy to edit', 404);
        }

        try {
            $vtt = new Captioning\Format\WebvttFile();
            $vtt->loadFromString(trim($trackFile->file_contents));
            $cues = $vtt->getCues();
            foreach ($cues as $index => $cue) {
                $cueName = 'cue' . $index;
                if (!isset($this->post[$cueName])) {
                    throw new \Exception('Missing expected track cue at ' . $cue->getStart() . ' - ' . $cue->getStop(), 404);
                }

                // update cue with new text
                $cue->setText(trim($this->post[$cueName]));

                // remove old cue;
                $vtt->removeCue($index);

                // add updated cue;
                $vtt->addCue($cue);
            }
            $vtt->build();
            $trackFile->file_contents = preg_replace("/(\r?\n){2,}/", "\n\n", trim($vtt->getFileContent()));
            $trackFile->save();

            // Mux Media with updated track if active track
            if ($media->media_text_tracks_id == $track->id) {
                $muxer = new UNL_MediaHub_Muxer($media);
                $muxer->mux();
            }

        } catch(Exception $e) {
            throw new \Exception('Error saving track file: ' . $e->getMessage(), 404);
        }

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'The caption track has been updated.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }

    function handleAICaptionReview() {
        $trackId = !empty($this->post['text_track_id']) ? $this->post['text_track_id'] : 0;
        $comment_text = 'Reviewed copy from track id' . $trackId;
        $returnData = $this->subFuncCopyTextTrackFile($comment_text);

        try {
            $vtt = new Captioning\Format\WebvttFile();
            $vtt->loadFromString(trim($returnData['trackFile']->file_contents));
            $cues = $vtt->getCues();
            foreach ($cues as $index => $cue) {
                $cueName = 'cue' . $index;
                if (!isset($this->post[$cueName])) {
                    throw new \Exception('Missing expected track cue at ' . $cue->getStart() . ' - ' . $cue->getStop(), 404);
                }

                // update cue with new text
                $cue->setText(trim($this->post[$cueName]));

                // remove old cue;
                $vtt->removeCue($index);

                // add updated cue;
                $vtt->addCue($cue);
            }
            $vtt->build();
            $returnData['trackFile']->file_contents = preg_replace("/(\r?\n){2,}/", "\n\n", trim($vtt->getFileContent()));
            $returnData['trackFile']->save();

            if (isset($this->post['activate_after_review']) && $this->post['activate_after_review'] === '1') {
                $returnData['media']->setTextTrack($returnData['track']);
            }

            // Mux Media with updated track if active track
            if ($returnData['media']->media_text_tracks_id == $returnData['trackID']) {
                $muxer = new UNL_MediaHub_Muxer($returnData['media']);
                $muxer->mux();
            }

        } catch(Exception $e) {
            throw new \Exception('Error saving track file: ' . $e->getMessage(), 404);
        }

        UNL_MediaHub::redirect($returnData['media']->getEditCaptionsURL());
    }
    
    function handleAmara()
    {
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);
        
        if (!$media) {
            throw new Exception('Unable to find media', 404);
        }

        if (!$media->userHasPermission(UNL_MediaHub_AuthService::getInstance()->getUser(), UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
        
        $result = $media->updateAmaraCaptions();
        
        if (!$result) {
            //No tracks were found, fail early
            $notice = new UNL_MediaHub_Notice(
                'Error',
                'No amara captions could be found for this media',
                UNL_MediaHub_Notice::TYPE_DANGER
            );
            UNL_MediaHub_Manager::addNotice($notice);
            UNL_MediaHub::redirect($media->getEditCaptionsURL());

            return;
        }

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'The latest amara captions have been pulled.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }
    
    function handleRev()
    {
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);

        if (!$media) {
            throw new Exception('Unable to find media', 404);
        }

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        
        if (!$media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
        
        if (!isset($this->post['cost_object']) || empty($this->post['cost_object'])) {
            throw new Exception('A cost object number must be provided', 400);
        }
        
        $sanitized_co = preg_replace('/[^\d]/', '', $this->post['cost_object']);
        
        if (!is_numeric($sanitized_co)) {
            throw new Exception('The cost object number must be a number. It can not contain any other characters.', 400);
        }
        
        $length = strlen($sanitized_co);
        if ($length < 10 || $length > 13) {
            throw new Exception('The cost object number must be between 10 and 13 digits', 400);
        }
        
        $existing_orders = new UNL_MediaHub_RevOrderList(array(
            'media_id_not_complete' => $media->id
        ));
        
        if ($existing_orders->count()) {
            throw new Exception('A pending order already exists for this media. Please wait for the existing order to finish.', 400);
        }
        
        $order_record = new UNL_MediaHub_RevOrder();
        $order_record->media_id = $media->id;
        $order_record->costobjectnumber = $sanitized_co;
        $order_record->uid = $user->uid;
        $order_record->status = UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_CREATED;
        
        if (isset($this->post['media_duration'])) {
            $order_record->estimate = $this->post['estimate'];
            $order_record->media_duration = $this->post['media_duration'];
        }
        
        $order_record->save();

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'A caption order has been placed, it should be completed within 24 hours.',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);
        
        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }

    protected function handleTranscriptions()
    {
        // Try to contact AI captioning website
        // Get response and parse
        // Save Job Id to DB

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);
        if (!$media) {
            throw new Exception('Unable to find media', 404);
        }
        if (!$media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        // Set up variables for transcriber
        $media_url = $media->getURL() . '/file';

        $ai_captioning = new UNL_MediaHub_TranscriptionAPI();
        $job_id = $ai_captioning->create_job($media_url);
        if ($job_id === false) {
            throw new Exception('Could Not Create New Captioning Job. Please reach out to an administrator.', 500);
        }

        $media->transcription($job_id, $user->uid, false);

        $notice = new UNL_MediaHub_Notice(
            'Success',
            'Ai Captioning Job Has Been Created',
            UNL_MediaHub_Notice::TYPE_SUCCESS
        );
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }

    protected function downloadRev()
    {
        $order = UNL_MediaHub_RevOrder::getById($this->post['order_id']);

        if (!$order) {
            throw new Exception('Unable to find order', 404);
        }

        $media = UNL_MediaHub_Media::getById($order->media_id);

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }

        if (!isset($this->post['format']) || empty($this->post['format'])) {
            throw new Exception('A format must be provided', 400);
        }

        $rev = UNL_MediaHub_RevAPI::getRevClient();

        if (!$rev) {
            throw new Exception('Unable to get the Rev client', 503);
        }
        
        $content = '';
        
        try {
            $rev_order = $rev->getOrder($order->rev_order_number);

            $attachments = $rev_order->getAttachments();

            $newest_attachment = false;
            foreach ($attachments as $attachment) {
                if ($attachment->isMedia()) {
                    //Only save non-media attachments
                    continue;
                }
                
                //Get the last caption attachment (newest)
                $newest_attachment = $attachment;
            }

            if ($newest_attachment) {
                $content = $newest_attachment->getContent('.' . $this->post['format']);
            }
        } catch(\RevAPI\Exception\RequestException $e) {
            throw new Exception('There was an error requesting captions');
        }
        
        header('Content-Disposition: attachment; filename=' . $media->title . '.' . $this->post['format']);

        echo $content;
        exit();
    }
    
    function setActiveTextTrack()
    {
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);

        if (!$media) {
            throw new Exception('Unable to find media', 404);
        }

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
        
        if (!isset($this->post['text_track_id'])) {
            throw new Exception('Post data must include text_track_id.', 400);
        }
        
        $text_track = UNL_MediaHub_MediaTextTrack::getById($this->post['text_track_id']);
        
        if (!$text_track) {
            throw new Exception('Unable to find text track.', 400);
        }
        
        if ($text_track->media_id != $media->id) {
            throw new Exception('That text track does not belong to the this media', 400);
        }
        
        $media->setTextTrack($text_track);
        
        $notice = new UNL_MediaHub_Notice('Success', 'The active caption track has been updated', UNL_MediaHub_Notice::TYPE_SUCCESS);
        UNL_MediaHub_Manager::addNotice($notice);

        UNL_MediaHub::redirect($media->getEditCaptionsURL());
    }
    
    function retryTranscodingJob()
    {
        $media = UNL_MediaHub_Media::getById($this->post['media_id']);

        if (!$media) {
            throw new Exception('Unable to find media', 404);
        }

        $user = UNL_MediaHub_AuthService::getInstance()->getUser();

        if (!$media->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE)) {
            throw new Exception('You do not have permission to edit this media.', 403);
        }
        
        $last_job = $media->getMostRecentTranscodingJob();
        
        if (!$last_job) {
            throw new Exception('There is no transcoding job to retry.', 400);
        }
        
        if (!$last_job->isError()) {
            throw new Exception('The last job was not an error, so it can not be retried.', 400);
        }
        
        //Start a new transcoding job
        $media->transcode($last_job->job_type);

        UNL_MediaHub::redirect(UNL_MediaHub_Manager::getURL() . '?view=addmedia&id='.$media->id);
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

}