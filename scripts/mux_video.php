<?php
require_once dirname(__FILE__).'/../config.inc.php';

function ffmpeg_mux_video($video_file, $output_file, array $subtitles) {
    //This should copy the video and audio tracks while replacing the subtitle tracks. Thus, old subtitles will be removed.
    $command = UNL_MediaHub::getRootDir() . '/ffmpeg/ffmpeg';
    $command .= ' -y'; //overwrite output files
    //$command .= ' -v fatal'; //only show fatal errors
    $command .= ' -i ' . $video_file; //Video input is test.mp4
    foreach ($subtitles as $subtitle) {
        $command .= ' -f srt'; //Caption format is .srt
        $command .= ' -i ' . $subtitle['file']; //Second input is the caption file
    }
    $command .= ' -c:v copy'; //copy videon
    $command .= ' -c:a copy'; //copy audio
    $command .= ' -c:s mov_text'; //Create a move_text subtitle
    $command .= ' -map 0'; //Map the video to the output
    foreach ($subtitles as $index=>$subtitle) {
        //Figure out s:s:0 syntax
        $command .= ' -metadata:s:s:' . $index . ' language=' . $subtitle['language']; //set subtitle language to eng
        $command .= ' -map ' . ($index+1) . ':0'; //Map all subtitles to the output
    }
    $command .= ' ' . $output_file; //Output file

    exec($command, $output, $result);
    
    return $result;
}

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
    echo "This program requires 1 arguments.".PHP_EOL;
    echo "mux_video.php video-id".PHP_EOL;
} else {
    require_once dirname(__FILE__).'/../config.inc.php';

    $my = new UNL_MediaHub();

    $media = UNL_MediaHub_Media::getById($_SERVER['argv'][1]);
    
    if (!$media) {
        echo 'Video Not Found' . PHP_EOL;
        exit();
    }
    
    $local_file_name = $media->getLocalFileName();
    $local_file_name_extension = pathinfo($local_file_name, PATHINFO_EXTENSION);
    $tmp_media_file = UNL_MediaHub::getRootDir() . '/tmp/' . $media->id . '.' . $local_file_name_extension;
    
    if (!copy($local_file_name, $tmp_media_file)) {
        echo 'unable to copy video'; exit();
    }
    
    $track = UNL_MediaHub_MediaTextTrack::getById($media->media_text_tracks_id);

    if (!$track) {
        echo 'No Tracks Found' . PHP_EOL;
        exit();
        //TODO: Remove the current track from the video file
    }

    $files = $track->getFiles();

    if (empty($files->items)) {
        echo 'No Track files found' . PHP_EOL;
        exit();
        //TODO: Remove the current tracks from the video file
    }
    
    $srt_files = array();
    
    foreach ($files->items as $file) {
        /**
         * @var $file UNL_MediaHub_MediaTextTrackFile
         */
        
        //TODO: only get webVTT tracks
        $tmp_srt_file = UNL_MediaHub::getRootDir() . '/tmp/' . $media->id . '_' . $file->language . '.srt';
        
        try
        {
            $vtt = new Captioning\Format\WebvttFile();
            $vtt->loadFromString($file->file_contents);
            $srt = $vtt->convertTo('subrip');
            //TODO: save as tmp.
            
            $srt->save($tmp_srt_file);
            
            //The language is stored in the database as iso639-1
            //ffmpeg requires iso639-2, so we need to convert it.
            $adapter = new \Conversio\Adapter\LanguageCode();
            $options = new \Conversio\Adapter\Options\LanguageCodeOptions();
            $options->setOutput('iso639-2/b');
            $converter = new \Conversio\Conversion($adapter);
            $converter->setAdapterOptions($options);
            
            $srt_files[] = array(
                'language' => $converter->filter($file->language),
                'file' => $tmp_srt_file,
            );
        }
        catch(Exception $e)
        {
            echo "Error: ".$e->getMessage()."\n"; exit();
        }
    }

    $result = ffmpeg_mux_video($media->getLocalFileName(), $tmp_media_file, $srt_files);
    var_dump($result);
}
