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
            throw new Exception('A text_file_id must be specified.', 400);
        }

        $file = UNL_MediaHub_MediaTextTrackFile::getById($options['text_file_id']);
        if (!$file) {
            throw new Exception('The text track file was not found', 404);
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