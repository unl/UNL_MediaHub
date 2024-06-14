<?php
class UNL_MediaHub_TranscriptionAPI
{
    /**
     * URL to the Ai captioning site (trailing slash important)
     * SHOULD NOT INCLUDE API ROUTE
     */
    public static $captioning_url = 'https://transcriptionist.unl.edu/';

    /**
     * API Key
     */
    public static $captioning_api_key = '';

    /**
     * SSL Verification
     */
    public static $verify = true;

    public static $api_status_route = 'api';
    public static $create_job_route = 'api/job';
    public static $job_status_route = 'api/job/{job_id}/status';
    public static $job_error_route = 'api/job/{job_id}/error';
    public static $job_output_route = 'api/job/{job_id}/output';

    public static $caption_format = 'vtt';

    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * @return array|bool false on error, json decoded array of values from the transcriptionist
     */
    public function api_status()
    {
        try {
            $response = $this->guzzle->get(
                self::$captioning_url . self::$api_status_route,
                [
                    'verify' => self::$verify,
                ]
            );

            $response = json_decode($response->getBody());
            if (!isset($response)) {
                throw new Exception('No response');
            }

            return $response;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $media_url url of the media to be captioned
     * @return string|bool false on error, string of job id
     */
    public function create_job(string $media_url)
    {
        try {
            $response = $this->guzzle->post(
                self::$captioning_url . self::$create_job_route,
                [
                    'form_params' => [
                        'media_url' => $media_url,
                        'output_format' => self::$caption_format,
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
        $status_url = self::$captioning_url . str_replace('{job_id}', $job_id, self::$job_status_route);
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
     * @param string $job_id the job id to get the error message of
     * @return string|bool false on error, string of job error message
     */
    public function get_error(string $job_id)
    {
        $status_url = self::$captioning_url . str_replace('{job_id}', $job_id, self::$job_error_route);
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
        $output_url = self::$captioning_url . str_replace('{job_id}', $job_id,  self::$job_output_route);
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
