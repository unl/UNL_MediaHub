<?php
class UNL_MediaHub_TranscriptionManager
{
    public $options = array();

    public $output;

    private $jobsList;
    private $formatted_data;

    public function __construct($options = array()) {
        $this->options = $options + $this->options;

        $api = new UNL_MediaHub_TranscriptionAPI();
        $api_status = $api->api_status();
        $this->formatted_data = [
            "status" => $api_status !== false ? 'OK' : 'FAILED',
            "data" => $api_status !== false ? $api_status : []
        ];

        if ($this->options['format'] === 'json') {
            $this->output = json_encode($this->formatted_data);
        } else {
            $this->jobsList = new UNL_MediaHub_TranscriptionJobList(array(
                'orderby' => 'datecreated',
                'order' => 'desc',
                'limit' => 50
            ));
        }
    }

    public static function getURL() {
        return UNL_MediaHub_Controller::$url . "transcription-manager";
    }

    public static function getJSONURL() {
        return UNL_MediaHub_Controller::$url . "transcription-manager/?format=json";
    }

    public function hasJobs() {
        return count($this->jobsList->items) > 0;
    }

    public function jobsCount() {
        return count($this->jobsList->items);
    }

    public function getJobs() {
        return $this->jobsList->items;
    }

    public function getAPIData() {
        return $this->formatted_data;
    }
}
