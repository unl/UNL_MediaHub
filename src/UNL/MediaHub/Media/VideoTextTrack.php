<?php
class UNL_MediaHub_Media_VideoTextTrack
{
    /**
     * @var UNL_MediaHub_Media
     */
    public $media;
    
    protected $track = false;
    
    public function __construct($options, $format = 'vtt')
    {
        if (!isset($options['id'])) {
            throw new Exception('Unknown media', 404);
        }
        
        if (!isset($options['text_file_id'])) {
            //Default to the english version (this is more work on the database, and should be discouraged).
            $media = UNL_MediaHub_Media::getById($options['id']);
            
            if (!$media) {
                throw new Exception('Media record could not be found', 404);
            }
            
            if (empty($media->media_text_tracks_id)) {
                throw new Exception('This media has no text track', 404);
            }

            $track = UNL_MediaHub_MediaTextTrack::getById($media->media_text_tracks_id);

            if (!$track) {
                throw new Exception('A text track could not be found', 404);
            }

            $files = $track->getFiles();

            foreach ($files->items as $track_file) {
                if ('en' === $track_file->language) {
                    $file = $track_file;
                }
            }
            
            if (empty($file)) {
                throw new Exception('An english text track could not be found', 404);
            }
        } else {
            $file = UNL_MediaHub_MediaTextTrackFile::getById($options['text_file_id']);
            if (!$file) {
                throw new Exception('The text track file was not found', 404);
            }
        }
        
        if (isset($options['download'])) {
            $file_name = $file->id . '-' .$file->language . '.'. $file->format;
            header("Content-disposition: attachment; filename=\"$file_name\"");
        }
        
        $this->track = $file->file_contents;
    }
    
    public function getTextTrack()
    {
        return $this->track;
    }
}