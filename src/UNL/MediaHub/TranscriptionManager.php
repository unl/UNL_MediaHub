<?php
class UNL_MediaHub_TranscriptionManager
{
    public $options = array();

    public $output;

    public function __construct($options = array()) {
        $this->options = $options + $this->options;

        if ($this->options['format'] !== 'json') {
            header("Location: " . self::getURL());
            die();
        }

        $api = new UNL_MediaHub_TranscriptionAPI();
        $api_status = $api->api_status();

        $this->output = json_encode([
            "status" => $api_status !== false ? 'OK' : 'FAILED',
            "data" => $api_status !== false ? $api_status : []
        ]);
    }

    public static function getURL() {
        return UNL_MediaHub_Controller::$url . "transcription-manager/?format=json";
    }
}
