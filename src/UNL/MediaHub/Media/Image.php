<?php
class UNL_MediaHub_Media_Image
{
    protected $options;
    
    public function __construct(array $options)
    {
        $this->options = $options;

        //Validate input
        if (!isset($this->options['id'])) {
            throw new \Exception('You must provide a media_id', 400);
        }
    }
    
    public function getThumbnail()
    {
        $media_id = (int)$this->options['id'];

        /**
         * Create the cache key
         * Note that the cache key does not contain the time code (only the url of the media).
         * To create thumbnail at at specific time of the video, you must make a request to something like
         * thumbnail.php?rebuild&time=00:00:10.00&url=x
         * Subsequent requests to thumbnail.php?url=x will yield the last generated thumbnail
         */
        $directory = UNL_MediaHub::getRootDir() . '/www/uploads/thumbnails/'.$media_id;
        $file = $directory.'/original.jpg';
        
        /*
         * @var $file_use_default - this file is a placeholder which indicates that ffmpeg couldn't render an image. If this file exists, the default placeholder file should be used instead.
         */
        $file_use_default = $directory.'/use-default';

        if (!isset($this->options['rebuild']) && file_exists($file)) {
            //just a quick retrieval
            return $file;
        }

        if (!isset($this->options['rebuild']) && file_exists($file_use_default)) {
            //just a quick retrieval of the default placeholder
            return UNL_MediaHub::getRootDir() . '/data/video-placeholder.jpg';
        }

        if (!$media = UNL_MediaHub_Media::getById($media_id)) {
            throw new \Exception('media not found', 404);
        }

        $posterURL = $media->getPosterURL();
        if (!empty($posterURL)) {
            //handle custom posters
            UNL_MediaHub::redirect($posterURL);
            exit();
        }

        //Does the current user have permission?
        $user = UNL_MediaHub_AuthService::getInstance()->getUser();
        $user_can_edit = false;

        if ($user) {
            $user_can_edit = $media->userCanEdit(UNL_MediaHub_AuthService::getInstance()->getUser());
        }

        if (!$user_can_edit && file_exists($file)) {
            //A user can't create a thumbnail for a video if one already exists (unless they have permission to edit the video)
            throw new \Exception('User does not have permission to edit thumbnail', 400);
        }

        //figure out the time
        $time = '00:00:10.00';
        if ($user_can_edit
            && isset($this->options['time'])
            && preg_match('/^[\d]+\:[\d]{2}\:[\d]{2}(\.[\d]{2})?$/', $this->options['time'])) {
            //Allow customizing the time if the user has permission
            $time = escapeshellarg($this->options['time']);
        }

        //We need to cache data
        $url = $media->url;
        $return = array();
        $status = 1;

        if ($media->getLocalFileName()) {
            $url = $media->getLocalFileName();
        }

        $url = escapeshellarg($url);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        
        exec(UNL_MediaHub::getFfmpegPath() . " -i $url -ss $time -vcodec mjpeg -vframes 1 -f image2 $file -y", $return, $status);

        if ($status == 0 && file_exists($file)) {
            $media->dateupdated = date('Y-m-d H:i:s');
            $media->save();
            return $file;
        }
        
        //Indicate that we should use the default placeholder file instead for future requests
        touch($file_use_default);

        //Fall back to the default poster image
        return UNL_MediaHub::getRootDir() . '/data/video-placeholder.jpg';
    }
}
