<?php

class UNL_MediaHub_MuxerDBTest extends UNL_MediaHub_DBTests_DBTestCase
{
    /**
     * Test muxing videos, including muxing multiple languages and un-muxing video
     * @test
     */
    public function mux()
    {
        $this->prepareTestDB();

        $data_dir = dirname(dirname(__DIR__)) . '/data';
        $mediainfo = new \Mhor\MediaInfo\MediaInfo();
        
        $media = UNL_MediaHub_Media::getById(1);
        //Add some captions to the video
        
        //Add a vtt
        $text_track = new UNL_MediaHub_MediaTextTrack();
        $text_track->media_id = $media->id;
        $text_track->source = UNL_MediaHub_MediaTextTrack::SOURCE_AMARA;
        $text_track->save();
        
        //English track
        $text_track_file = new UNL_MediaHub_MediaTextTrackFile();
        $text_track_file->media_text_tracks_id = $text_track->id;
        $text_track_file->kind = UNL_MediaHub_MediaTextTrackFile::KIND_CAPTION;
        $text_track_file->format = UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT;
        $text_track_file->language = 'en';
        $text_track_file->file_contents = file_get_contents($data_dir.'/sample.vtt');
        $text_track_file->save();

        //Spanish track
        $text_track_file = new UNL_MediaHub_MediaTextTrackFile();
        $text_track_file->media_text_tracks_id = $text_track->id;
        $text_track_file->kind = UNL_MediaHub_MediaTextTrackFile::KIND_CAPTION;
        $text_track_file->format = UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT;
        $text_track_file->language = 'es';
        $text_track_file->file_contents = file_get_contents($data_dir.'/sample.vtt');
        $text_track_file->save();
        
        //link up the text track
        $media->media_text_tracks_id = $text_track->id;
        $media->save();

        //Start up the muxer
        $muxer = new UNL_MediaHub_Muxer($media);
        
        //Copy over un-muxed video
        copy($data_dir.'/un-muxed.mp4', UNL_MediaHub::getRootDir() . '/www/uploads/a.mp4');

        //Do the muxing!
        $result = $muxer->mux();

        $this->assertTrue($result, 'muxing should be successful');
        
        //We should have a text track!
        $details = $mediainfo->getInfo($media->getLocalFileName());
        $subtitles = $details->getSubtitles();
        $this->assertNotEmpty($subtitles);
        
        $languages = array();
        foreach ($subtitles as $subtitle) {
            $languages[] = $subtitle->get('language')[0];
        }
        
        //Make sure it has both languages
        $this->assertContains('en', $languages);
        $this->assertContains('es', $languages);
        
        //Make sure video and audio streams are still there
        $this->assertEquals(1, count($details->getAudios()), 'there should be 1 audio track');
        $this->assertEquals(1, count($details->getVideos()), 'there should be 1 video track');
        $this->assertEquals(2, count($details->getSubtitles()), 'there should be 2 subtitle tracks');
        
        //Test removing subtitles
        $media->media_text_tracks_id = NULL;
        $media->save();
        
        //Do the muxing
        $result = $muxer->mux();
        
        $this->assertTrue($result, 'muxing should be successful');

        //Test
        $details = $mediainfo->getInfo($media->getLocalFileName());
        $subtitles = $details->getSubtitles();
        $this->assertEmpty($subtitles, 'there should be zero subtitles');

        //Make sure audo and video was transferred correctly
        $this->assertEquals(1, count($details->getAudios()), 'there should be 1 audio track');
        $this->assertEquals(1, count($details->getVideos()), 'there should be 1 video track');
    }

    /**
     * @test
     */
    public function testRemoveExistingTracks()
    {
        $this->prepareTestDB();

        $data_dir = dirname(dirname(__DIR__)) . '/data';
        $mediainfo = new \Mhor\MediaInfo\MediaInfo();

        $media = UNL_MediaHub_Media::getById(1);
        //Add some captions to the video

        //Add a vtt
        $text_track = new UNL_MediaHub_MediaTextTrack();
        $text_track->media_id = $media->id;
        $text_track->source = UNL_MediaHub_MediaTextTrack::SOURCE_AMARA;
        $text_track->save();

        //English track
        $text_track_file = new UNL_MediaHub_MediaTextTrackFile();
        $text_track_file->media_text_tracks_id = $text_track->id;
        $text_track_file->kind = UNL_MediaHub_MediaTextTrackFile::KIND_CAPTION;
        $text_track_file->format = UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT;
        $text_track_file->language = 'en';
        $text_track_file->file_contents = file_get_contents($data_dir.'/sample.vtt');
        $text_track_file->save();

        //link up the text track
        $media->media_text_tracks_id = $text_track->id;
        $media->save();

        //Start up the muxer
        $muxer = new UNL_MediaHub_Muxer($media);

        //Copy over muxed video
        copy($data_dir.'/muxed.mp4', UNL_MediaHub::getRootDir() . '/www/uploads/a.mp4');

        //Do the muxing!
        $result = $muxer->mux();

        $this->assertTrue($result, 'muxing should be successful');

        //We should have one text track!
        $details = $mediainfo->getInfo($media->getLocalFileName());
        $subtitles = $details->getSubtitles();
        
        $this->assertEquals(1, count($subtitles));
    }
}
