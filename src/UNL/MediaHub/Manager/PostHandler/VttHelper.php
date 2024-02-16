<?php
/**
 * Class which handles post data and media uploads
 * @author tneumann9
 */
class UNL_MediaHub_Manager_PostHandler_VttHelper
{
    private $srt_file_contents;

    public function __construct(string $srt_file_contents) {
        $this->srt_file_contents = $srt_file_contents;
    }

    public function get_vtt_file():string {

        $vttContent = "";

        // Replace SRT-specific formatting with VTT formatting
        $vttContent = str_replace("\r\n", "\n", $this->srt_file_contents);
        $vttContent = preg_replace('/(\d{2}:\d{2}:\d{2}),(\d{3})/', '$1.$2', $vttContent);
        $vttContent = "WEBVTT\n\n" . $vttContent;

        return $vttContent;
    }
}