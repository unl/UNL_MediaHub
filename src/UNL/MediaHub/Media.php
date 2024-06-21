<?php
use Ramsey\Uuid\Uuid;

class UNL_MediaHub_Media extends UNL_MediaHub_Models_BaseMedia implements UNL_MediaHub_MediaInterface
{
    const CODEC_REMOTE_VIDEO = 'remote-video-is-unknown';
    const ASPECT_16x9 = '16:9';
    const ASPECT_9x16 = '9:16';
    const ASPECT_4x3 = '4:3';
    const ASPECT_3x4 = '3:4';
    const ASPECT_1x1 = '1:1';
    const POSTER_PATH = 'uploads/posters/';
    
    /**
     * Get a piece of media by PK.
     *
     * @param int $id ID of the media.
     *
     * @return UNL_MediaHub_Media
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
    
    /**
     * Get a piece of media by URL
     *
     * @param string $url URL to the video/audio file
     * 
     * @return UNL_MediaHub_Media
     */
    public static function getByURL($url)
    {
        $media = Doctrine::getTable(__CLASS__)->findOneByURL($url);
        if ($media) {
            return $media;
        }
        return false;
    }
    
    public static function formatNumber($number, $precision = 0)
    {
        $suffix = '';
        $exp = floor(log($number, 10));
        
        if ($exp >= 6) {
            $exp = 6;
            $suffix = 'M';
        } elseif ($exp >= 4) {
            $exp = 3;
            $suffix = 'k';
        } else {
            $exp = 0;
        }
        
        $value = round($number / pow(10, $exp), $precision) . $suffix;
        return $value;
    }
    
    /**
     * called before an item is inserted to the database
     * 
     * @param $event
     * 
     * @return void
     */
    public function preInsert($event)
    {
        $uuid = Uuid::uuid1();
        $this->UUID = $uuid->toString();
        $this->setContentType();
    }

    /**
     * called before an item is updated in the database
     * 
     * @param $event
     * 
     * @return void
     */
    public function preUpdate($event)
    {
        $this->setContentType();
    }
    
    /**
     * called after an item is inserted into the database
     * 
     * @param $event
     * 
     * @return void
     */
    public function postInsert($event)
    {
        if ($this->isVideo()) {
            $this->setMRSSThumbnail();
            // $this->setProjection();
        }
        $this->setMRSSContent();
    }

    public function postSave($event)
    {
//        var_dump('postsave');
//        $this->setMRSSThumbnail();
//        $this->setMRSSContent();
    }
    
    /**
     * Sets the thumbnail media rss element.
     * 
     * @return true
     */
    function setMRSSThumbnail()
    {
        if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_media::mediaHasElement($this->id, 'thumbnail')) {
            // all ok
        } else {
            $element = new UNL_MediaHub_Feed_Media_NamespacedElements_media();
            $element->media_id = $this->id;
            $element->element = 'thumbnail';
        }
        $attributes = array('url' => $this->getThumbnailURL(),
                            //width="75" height="50" time="12:05:01.123"
                            );
        $element->attributes = $attributes;
        $element->value = '';
        $element->save();
        return true;
    }

    function getVideoDimensions($force = false)
    {
        if (!$force && $element = UNL_MediaHub_Feed_Media_NamespacedElements_media::mediaHasElement($this->id, 'content')) {
            return array('width'=>$element->attributes['width'], 'height'=>$element->attributes['height']);
        }
        
        return $this->findVideoDimensions();
    }
    
    function findVideoDimensions()
    {
        if (!$this->getLocalFileName()) {
            //We need the media to be local to find the dimensions
            return false;
        }
        
        try {
            $mediainfo = UNL_MediaHub::getMediaInfo();
            $details = $mediainfo->getInfo($this->getLocalFileName());
            $videos = $details->getVideos();

            if (!isset($videos[0])) {
                //video track might be missing
                return false;
            }

            $width = $videos[0]->get('width')->getAbsoluteValue();
            $height = $videos[0]->get('height')->getAbsoluteValue();

            if (!$width || !$height) {
                return false;
            }
            
            return [
                'width' => $width,
                'height' => $height,
            ];
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the aspect ratio of the video. Only supports 4x3 or 16x9 or 9x16
     * 
     * @return bool|string
     */
    function getAspectRatio()
    {
        if (!$this->getLocalFileName()) {
            //We need the media to be local to find the dimensions
            return false;
        }

        try {
            $mediainfo = UNL_MediaHub::getMediaInfo();
            $details = $mediainfo->getInfo($this->getLocalFileName());
            $videos = $details->getVideos();

            if (!isset($videos[0])) {
                //video track might be missing
                return false;
            }

            $rotation = $videos[0]->get('rotation')[0] ?? '0';

            $ratio = $videos[0]->get('display_aspect_ratio')->getTextvalue();
            $ratioFlipped = $rotation === '90.000' || $rotation === '270.000';

        } catch (\Exception $e) {
            return false;
        }

        if ($ratio == '1.000') {
            return self::ASPECT_1x1;
        }

        if ((!$ratioFlipped && $ratio == '4:3') || ($ratioFlipped && $ratio == '0.750')) {
            return self::ASPECT_4x3;
        }

        if ((!$ratioFlipped && $ratio == '0.750') || ($ratioFlipped && $ratio == '4:3')) {
            return self::ASPECT_3x4;
        }

        if ((!$ratioFlipped && $ratio == '0.562') || ($ratioFlipped && $ratio == '16:9')) {
            return self::ASPECT_9x16;
        }

        //Otherwise assume 16x9
        return self::ASPECT_16x9;
    }

    /**
     * Set the Media RSS, mrss content namespaced element
     * 
     * @return true
     */
    function setMRSSContent()
    {
        if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_media::mediaHasElement($this->id, 'content')) {
            // all good
        } else {
            $element = new UNL_MediaHub_Feed_Media_NamespacedElements_media();
            $element->media_id = $this->id;
            $element->element = 'content';
        }
        $attributes = array('url'      => $this->url,
                            'fileSize' => $this->length,
                            'type'     => $this->type,
                            'lang'     => 'en');
        if ($this->isVideo()) {
            $result = $this->getVideoDimensions(true)?:['width'=>null, 'height'=>null];
            
            $attributes['width']  = $result['width'];
            $attributes['height'] = $result['height'];
        }

        if (isset($element->attributes) && is_array($element->attributes)) {
            $attributes = array_merge($element->attributes, $attributes);
        }
        $element->attributes = $attributes;
        $element->value = '';
        $element->save();
        return true;
    }


    /**
     * Set the Media RSS, projection element
     * 
     * @return true
     */
    function setProjection($projection = false)
    {
        if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::mediaHasElement($this->id, 'projection')) {
            // all good
        } else {
            $element = new UNL_MediaHub_Feed_Media_NamespacedElements_mediahub();
            $element->media_id = $this->id;
            $element->element = 'projection';
        }
        $element->attributes = array();
        $element->value = $projection;
        $element->save();
        return true;
    }

    /**
     * Get the Media RSS, projection element
     * 
     * @return true
     */
    function getProjection()
    {
        if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::mediaHasElement($this->id, 'projection')) {
            return $element->value;
        }
        return false;
    }

    /**
     * Determine if this is a 360 video
     * 
     * @return bool
     */
    public function is360()
    {
        if (!$projection = $this->getProjection()) {
            return false;
        }

        return $projection === 'equirectangular';
    }
    
    /**
     * Check if this media is a video file.
     * 
     * @param string URL or content-type
     * 
     * @return bool
     */
    public function isVideo()
    {
        return UNL_MediaHub::isVideo($this->type);
    }

    /**
     * Get the HLS master playlist path
     * 
     * @return string
     */
    public function getHLSPlaylistPath()
    {
        return UNL_MediaHub_Manager::getUploadDirectory() . '/'. $this->UUID . '/media.m3u8';
    }
    
    public function getHLSPlaylistUrl()
    {
        return UNL_MediaHub_Controller::$url . 'uploads/' . $this->UUID . '/media.m3u8';
    }

    /**
     * Determine if this video supports HLS
     * 
     * @return bool
     */
    public function hasHls()
    {
        return file_exists($this->getHLSPlaylistPath());
    }

    /**
     * @return UNL_MediaHub_TranscodingJob|false
     */
    public function getMostRecentTranscodingJob()
    {
        $jobs = new UNL_MediaHub_TranscodingJobList(array(
            'media_id' => $this->id,
            'orderby' => 'id',
            'order' => 'desc',
            'limit' => 1
        ));

        if (!isset($jobs->items[0])) {
            return false;
        }

        //Return the first item
        return $jobs->items[0];
    }

    /**
     * @return UNL_MediaHub_TranscriptionJob|false
     */
    public function getMostRecentTranscriptionJob()
    {
        $jobs = new UNL_MediaHub_TranscriptionJobList(array(
            'media_id' => $this->id,
            'orderby' => 'id',
            'order' => 'desc',
            'limit' => 1
        ));

        if (!isset($jobs->items[0])) {
            return false;
        }

        //Return the first item
        return $jobs->items[0];
    }
    
    /**
     * Sets the content type for the media being added.
     * 
     * @return bool
     */
    function setContentType()
    {
        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $context = stream_context_create(
            array(
                'http'=>array(
                    'method'     => 'GET',
                    'user_agent' => 'UNL MediaHub/mediahub.unl.edu'
                ),
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            )
        );

        $result = @file_get_contents($this->url, null, $context, 0, 8);
        if (false === $result) {
            // Could not retrieve the info about this piece of media
            return false;
        }

        if (false !== $http_response_header && count($http_response_header)) {
            foreach($http_response_header as $header) {
                if (strpos($header, 'Content-Type: ') !== false) {
                    $this->type = substr($header, 14);
                }
                if (strpos($header, 'Content-Length: ') !== false) {
                    $this->length = substr($header, 16);
                }
            }
        }
        return true;
    }

    function getFeeds()
    {
        return new UNL_MediaHub_FeedList(array('limit'=>null, 'filter'=>new UNL_MediaHub_FeedList_Filter_WithMediaId($this->id)));
    }

    function delete(Doctrine_Connection $conn = null)
    {
        $feeds = $this->getFeeds();
        $feeds->run();

        if (count($feeds->items)) {
            foreach ($feeds->items as $feed) {
                $feed->removeMedia($this);
            }
        }

        try {
            foreach (array('UNL_MediaHub_Feed_Media_NamespacedElements_itunesu',
                           'UNL_MediaHub_Feed_Media_NamespacedElements_itunes',
                           'UNL_MediaHub_Feed_Media_NamespacedElements_media',
                           'UNL_MediaHub_Feed_Media_NamespacedElements_boxee') as $ns_class) {
                foreach ($this->$ns_class as $namespaced_element) {
                    $namespaced_element->delete();
                }
            }
        } catch (Exception $e) {
            // Error, just skip this for now.
        }
        
        $local_file = $this->getLocalFileName();
        
        if (!parent::delete($conn)) {
            return false;
        }
        
        if ($local_file && !is_dir($local_file)) {
            //Delete the file, and make sure it isn't a directory for some unknown reason.
            unlink($local_file);
        }
        
        //Media can have its own upload directory associated with it. Delete it if it exists
        $this->deleteUploadDirectory();

        // Remove local poster file if exists
        $this->removeLocalPosterFile();
        
        return true;
    }
    
    protected function deleteUploadDirectory()
    {
        if (empty($this->UUID)) {
            //This media isn't new enough to have a uuid directory... don't even try
            return false;
        }
        
        $upload_directory = UNL_MediaHub_Manager::getUploadDirectory() . '/'. $this->UUID;

        if (!file_exists($upload_directory) || !is_dir($upload_directory)) {
            return false;
        }
        
        $it = new RecursiveDirectoryIterator($upload_directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        
        rmdir($upload_directory);
    }

    /**
     * Get the local file name for this media.  It will be an absolute path if found.
     * 
     * @return bool|string
     */
    public function getLocalFileName()
    {
        return self::getLocalFileNameByURL($this->url);
    }

    /**
     * @param $url
     * @return bool|string
     */
    public static function getLocalFileNameByURL($url)
    {
        $agnostic_file_url = preg_replace('/^https?:\/\//', '//', $url, 1);
        $agnostic_uploads_url = preg_replace('/^https?:\/\//', '//', UNL_MediaHub_Controller::getURL() . 'uploads/', 1);

        if (strpos($agnostic_file_url, $agnostic_uploads_url) !== 0) {
            return false;
        }

        $file = UNL_MediaHub_Manager::getUploadDirectory() . '/' . str_replace($agnostic_uploads_url, '', $agnostic_file_url);

        if (!file_exists($file)) {
            return false;
        }

        return $file;
    }

    /**
     * Get the media
     * 
     * This will also correct broken URLs for local media (such as http -> https)
     * 
     * @return bool|string
     */
    public function getMediaURL()
    {
        if (!$local_file = $this->getLocalFileName()) {
            return $this->url;
        }

        return
            UNL_MediaHub_Controller::getURL() . 'uploads/' . str_replace(UNL_MediaHub_Manager::getUploadDirectory().'/', '', $local_file);
    }

    /**
     * Get the tags associated with this media file
     * 
     * @return array()
     */
    function getTags()
    {
        $tags = array();
        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_itunes';
        if ($element = call_user_func($class .'::mediaHasElement', $this->id, 'keywords')) {
            $tags = explode(',', $element->value);
            array_walk($tags, 'trim');
        }
        return $tags;
    }

    /**
     * Add a new tag for this media.
     * 
     * @param string $newTag Tag or comma separated list of tags to add
     */
    function addTag($newTag)
    {
        $tags = $this->getTags();
        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_itunes';
        if (!in_array(strtolower($newTag), $tags)) {
            array_push($tags, strtolower($newTag));
            sort($tags);
            $tagStr = implode(",", $tags);
            return call_user_func($class .'::mediaSetElement', $this->id, 'keywords', $tagStr);
        }
        return false;
    }

    /**
     * Check if poster is a local custom upload
     *
     * @return boolean
     */
    function isLocalPosterURL() {
        return !empty($this->poster) && strpos($this->poster, self::POSTER_PATH . $this->id);
    }

    /**
     * Remove the local poster file if exists
     *
     * @return null
     */
    function removeLocalPosterFile() {
        if ($this->isLocalPosterURL()) {
            $pathParts = pathinfo(parse_url($this->poster, PHP_URL_PATH));
            $file = UNL_MediaHub::getRootDir() . '/www/' . self::POSTER_PATH . $this->id . '.' . $pathParts['extension'];
            if (file_exists($file)) {
                $this->poster = '';
                unlink($file);
            }
        }
    }

    /**
     * Process the uploaded poster image file. Sets $errors with any errors generated.
     *
     * @return null
     */
    function processPosterUpload($upload, &$errors) {
        $errors = array();
        if (!in_array($upload['type'], array('image/gif', 'image/jpeg', 'image/jpg', 'image/png'))) {
            $errors[] = 'Media poster image must be a gif, jpg, or png.';
            return;
        }

        $imageInfo = @getimagesize($upload['tmp_name']);
        if (!empty($imageInfo)) {
            $width = intval($imageInfo[0]);
            $height = intval($imageInfo[1]);
            if ($width > 1920) {
                $errors[] = 'Media poster image must not be wider than 1920 pixels.';
            } elseif ($height > 1920) {
                $errors[] = 'Media poster image must not be taller than 1920 pixels.';
            }
        }

        if (empty($errors)) {
            $this->removeLocalPosterFile();
            $pathParts = pathinfo($upload['name']);
            $posterFile = UNL_MediaHub::getRootDir() . '/www/' . self::POSTER_PATH . $this->id . '.' . $pathParts['extension'];
            move_uploaded_file($upload['tmp_name'], $posterFile);
            $this->poster = UNL_MediaHub_Controller::$url . self::POSTER_PATH . $this->id . '.' . $pathParts['extension'] . '?' . strtotime();
        }
    }

    /**
     * Get the URL of the poster thumbnail for this media
     *
     * @return string
     */
    function getPosterURL() {
        if (!empty($this->poster)) {
            $poster = $this->poster;
            if ($this->isLocalPosterURL()) {
                $poster .= '?'. strtotime($this->dateupdated);
            }
            return $poster;
        }
    }

    /**
     * Get the URL to the thumbnail for this media
     *
     * @return string
     */
    function getThumbnailURL()
    {
        $posterURL = $this->getPosterURL();
        if (!empty($posterURL)) {
            return $posterURL;
        }

        if (!$this->isVideo()) {
            return UNL_MediaHub_Controller::getURL().'templates/html/css/images/waveform.png';
        }
        
        $file = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/' . $this->id . '/original.jpg';
        if (file_exists($file)) {
            $cache_bust = strtotime($this->dateupdated);
            
            //Skip the generator script if we already have an image
            return UNL_MediaHub_Controller::$url . 'uploads/thumbnails/' . $this->id .'/original.jpg?'.$cache_bust;
        }
        
        return $this->getURL() . '/image';
    }

    function getStreamingURL()
    {
        if (false === strpos($this->url, 'http://real.unl.edu/podcast/')) {
            return false;
        }
        return str_replace('http://real.unl.edu/podcast/', 'rtmp://real.unl.edu/content/podcast/', $this->url);
    }

    public function getVideoTextTrackURL($format = 'srt')
    {
        return UNL_MediaHub_Controller::$url.'media/'.$this->id.'/'.$format;
    }
    
    static function getPossiblePrivacyValues()
    {
        $table = Doctrine::getTable('UNL_MediaHub_Media');
        $column = $table->getColumnDefinition('privacy');
        return $column['values'];
    }

    /**
     * Determine if this meets the caption requirement
     * 
     * @return bool
     */
    public function meetsCaptionRequirement()
    {
        if (false == UNL_MediaHub_Controller::$caption_requirement_date) {
            return true;
        }
        
        if (empty($this->media_text_tracks_id) 
            && strtotime($this->datecreated) > strtotime(UNL_MediaHub_Controller::$caption_requirement_date)) {
            return false;
        }
        
        return true;
    }

    /**
     * @return bool
     */
    public function canView(UNL_MediaHub_User $user = NULL)
    {
        $requires_membership = false;
        
        if (!$this->meetsCaptionRequirement()) {
            $requires_membership = true;
        }
        
        //If its not private or protected, anyone can view it.
        if ($this->privacy == 'PRIVATE' || $this->privacy == 'PROTECTED') {
            $requires_membership = true;
        }
        
        //If it doesn't require membership, anyone can view it.
        if (!$requires_membership) {
            return true;
        }

        // get user if not passed in
        if (empty($user)) {
            $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        }
        
        //At this point a user needs to be logged in.
        if (!($user instanceof UNL_MediaHub_User)) {
            return false;
        }

        // all logged in users may view protected media
        if ($user && $this->privacy == 'PROTECTED') {
            return true;
        }
        
        //Get a list of feeds for this user that contain this media.
        $feeds = new UNL_MediaHub_FeedList(array(
            'limit'=>null,
            'filter'=>new UNL_MediaHub_FeedList_Filter_ByUserWithMediaId($user, $this->id)
        ));

        //Can view only if they are a member of the at least one of the feeds (specific permissions don't matter).
        if (0 === count($feeds->items)) {
            return false;
        }
        
        return true;
    }

    /**
     * @param UNL_MediaHub_User $user
     * @return bool
     */
    public function userCanEdit(UNL_MediaHub_User $user)
    {
        return $this->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_UPDATE);
    }

    /**
     * @param UNL_MediaHub_User $user
     * @return bool
     */
    public function userCanDelete(UNL_MediaHub_User $user)
    {
        return $this->userHasPermission($user, UNL_MediaHub_Permission::USER_CAN_DELETE);
    }

    /**
     * @param UNL_MediaHub_User $user
     * @param $permission_id - the id of the permission to check against
     * @return bool
     */
    public function userHasPermission(UNL_MediaHub_User $user, $permission_id)
    {
        //Is the user logged in?
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (!$permission = UNL_MediaHub_Permission::getByID($permission_id)) {
            return false;
        }

        $feeds = new UNL_MediaHub_FeedList(array(
            'limit'=>null,
            'filter'=>new UNL_MediaHub_FeedList_Filter_ByUserWithMediaId($user, $this->id)
        ));

        $feeds->run();

        foreach($feeds->items as $feed) {
            if ($feed->userHasPermission($user, $permission)) {
                return true;
            }
        }

        return false;
    }
    
    public function getTextTrackURLs()
    {
        $track = UNL_MediaHub_MediaTextTrack::getById($this->media_text_tracks_id);
        
        if (!$track) {
            return array();
        }
        
        $files = $track->getFiles();
        
        if (empty($files->items)) {
            return array();
        }
        
        
        $urls = array();
        foreach ($files->items as $file) {
            $urls[$file->language] = $file->getURL();
        }
        
        return $urls;
    }

    public function getTextTracks()
    {
        $track = UNL_MediaHub_MediaTextTrack::getById($this->media_text_tracks_id);
        
        if (!$track) {
            return array();
        }
        
        $files = $track->getFiles();
        
        if (empty($files->items)) {
            return array();
        }
        
        
        $tracks = array();
        foreach ($files->items as $file) {
            $tracks[$file->language] = $file->file_contents;
        }
        
        return $tracks;
    }
    
    public function getAmaraTextTracks($format = 'vtt')
    {
        $api = new UNL_MediaHub_AmaraAPI();

        return $api->getTextTracks($this->url, $format);
    }

    /**
     * @return bool|UNL_MediaHub_DurationHelper
     */
    public function findDuration()
    {
        if (!$this->getLocalFileName()) {
            //We need the media to be local to find the duration
            return false;
        }
        //echo $this->getLocalFileName(); exit();
        try {
            $mediainfo = UNL_MediaHub::getMediaInfo();
            $details = $mediainfo->getInfo($this->getLocalFileName());
            $general = $details->getGeneral();
            
            if (null === $general) {
                //getGeneral() can return null and not throw an exception
                return false;
            }
            
            $duration = $general->get('duration');
            
            if (null === $duration) {
                return false;
            }

            return new UNL_MediaHub_DurationHelper((int)$duration->getMilliseconds());
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }

    /**
     * Pull amara captions for this video
     * 
     * @return bool
     */
    public function updateAmaraCaptions()
    {
        $tracks = $this->getAmaraTextTracks();

        if (empty($tracks)) {
            //No tracks were found, fail early
            return false;
        }

        $text_track = new UNL_MediaHub_MediaTextTrack();
        $text_track->media_id = $this->id;
        $text_track->source = UNL_MediaHub_MediaTextTrack::SOURCE_AMARA;
        $text_track->save();

        foreach ($tracks as $lang=>$track) {
            $text_track_file = new UNL_MediaHub_MediaTextTrackFile();
            $text_track_file->media_text_tracks_id = $text_track->id;
            $text_track_file->kind = UNL_MediaHub_MediaTextTrackFile::KIND_CAPTION;
            $text_track_file->format = UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT;
            $text_track_file->language = $lang;
            $text_track_file->file_contents = $track;
            $text_track_file->save();
        }

        //update the media to point to the new text track
        $this->setTextTrack($text_track);
        
        return true;
    }
    
    public function getEditCaptionsURL()
    {
        return UNL_MediaHub_Manager::getURL() . '?view=editcaptions&id=' . $this->id;
    }

    /**
     * Get the public URL for this media
     * 
     * @return string
     */
    public function getURL()
    {
        return UNL_MediaHub_Controller::getURL($this);
    }

    /**
     * @param UNL_MediaHub_MediaTextTrack $track
     * @return bool
     */
    public function setTextTrack(UNL_MediaHub_MediaTextTrack $track)
    {
        $this->media_text_tracks_id = $track->id;
        $this->dateupdated = date('Y-m-d H:i:s');
        $this->save();
        
        //now MUX!
        if (UNL_MediaHub_Controller::$auto_mux) {
            $muxer = new UNL_MediaHub_Muxer($this);
            return $muxer->mux();
        }
        
        return true;
    }

    /**
     * Get the codec used in this video
     *
     * @return bool
     */
    function getCodec()
    {
        if (!$this->getLocalFileName()) {
            //We need the media to be local to find the dimensions
            return self::CODEC_REMOTE_VIDEO;
        }

        try {
            $mediainfo = UNL_MediaHub::getMediaInfo();
            $details = $mediainfo->getInfo($this->getLocalFileName());
            $videos = $details->getVideos();

            if (!isset($videos[0])) {
                //video track might be missing
                return false;
            }

            return $videos[0]->get('format')->getShortName();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Determine weather or not the codec of this video is safe for the web
     * 
     * @return bool
     */
    function isWebSafe()
    {
        $unsafe = ['MPEG-4 Visual'];
        
        if (in_array($this->getCodec(), $unsafe)) {
            return false;
        }
        
        return true;
    }

    /**
     * @param $job_type
     * @return bool|UNL_MediaHub_TranscodingJob
     * @throws Doctrine_Connection_Exception
     * @throws Doctrine_Record_Exception
     * @throws Exception
     */
    function transcode($job_type, $uid = null)
    {
        $valid_job_types = array('hls', 'mp4');
        
        if (!in_array($job_type, $valid_job_types)) {
            //Invalid job type
            return false;
        }
        
        if (!$this->isVideo()) {
            //Don't transcode audio
            return false;
        }
        
        $job = new UNL_MediaHub_TranscodingJob();
        
        $job->media_id = $this->id;
        $job->job_type = $job_type;
        
        if ($uid) {
            $job->uid = $uid;
        } else {
            $uid = $this->uidcreated;
        }
        
        $job->uid = $uid;
        
        $job->save();
        
        return $job;
    }

    public function transcription(string $job_id, $uid=null, $auto_activate=true)
    {
        if (empty($job_id)) {
            return false;
        }

        $job = new UNL_MediaHub_TranscriptionJob();
        $job->media_id = $this->id;
        $job->job_id = $job_id;
        $job->auto_activate = intval($auto_activate);
        if ($uid) {
            $job->uid = $uid;
        } else {
            $uid = $this->uidcreated;
            $job->uid = $uid;
        }
        $job->save();
        return $job;
    }
}
