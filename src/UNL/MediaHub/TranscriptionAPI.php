<?php
class UNL_MediaHub_TranscriptionAPI
{
    public static $captioning_url = 'https://mediahub.unl.edu/';
    public static $captioning_api_key = '';
    public static $verify = true;

    public $create_job_route = 'api/job';
    public $job_status_route = 'api/job/{job_id}/status';
    public $job_output_route = 'api/job/{job_id}/output';

    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * @param string $media_url url of the media to be captioned
     * @param string $media_type file extension of the media to be captioned
     * @return string|bool false on error, string of job id
     */
    public function create_job(string $media_url, string $media_type)
    {
        try {
            $response = $this->guzzle->post(
                self::$captioning_url . $this->create_job_route,
                [
                    'form_params' => [
                        'media_url' => $media_url,
                        'media_type' => $media_type,
                        'output_format' => 'vtt',
                    ],
                    'headers' => [
                        'Authentication' => self::$captioning_api_key,
                    ],
                    'verify' => self::$verify,
                ]
            );

            $response = json_decode($response->getBody());
            if (!isset($response)) {
                throw new Exception('No response');
            }

            $job_id = $response->data->new_job_id ?? 0;
            if ($job_id === 0) {
                throw new Exception('No Job ID');
            }
            return $job_id;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $job_id the job id to get the status of
     * @return string|bool false on error, string of job status
     */
    public function get_status(string $job_id)
    {
        $status_url = self::$captioning_url . str_replace('{job_id}', $job_id, $this->job_status_route);
        try {
            $response = $this->guzzle->get(
                $status_url,
                [
                    'headers' => [
                        'Authentication' => self::$captioning_api_key,
                    ],
                    'verify' => self::$verify,
                ]
            );

            $response = json_decode($response->getBody());
            if (!isset($response)) {
                throw new Exception('No response');
            }

            $job_status = $response->data->job_status ?? '0';
            if ($job_status === 0) {
                throw new Exception('No Job Status');
            }
            return $job_status;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  @param string $job_id the job id to get the status of
     * @return string|bool false on error, string of job output captions
     */
    public function get_captions(string $job_id)
    {
        $output_url = self::$captioning_url . str_replace('{job_id}', $job_id, $this->job_output_route);
        try {
            $response = $this->guzzle->get(
                $output_url,
                [
                    'headers' => [
                        'Authentication' => self::$captioning_api_key,
                    ],
                    'verify' => self::$verify,
                ]
            );

            $response = json_decode($response->getBody());
            if (!isset($response)) {
                throw new Exception('No response');
            }

            $output_file_contents = $response->data->output_file_contents ?? '0';
            if ($output_file_contents === 0) {
                throw new Exception('No Caption File');
            }
            return $output_file_contents;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}